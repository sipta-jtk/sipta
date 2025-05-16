@extends('adminlte::page')

@section('title', 'Dashboard')
@php use Illuminate\Support\Str; @endphp


@section('content')
    @if($user->role_user === 'mahasiswa')
        <div class="row pt-3">
            <!-- Profil Mahasiswa -->
            <div class="col-md-6 d-flex">
                <div class="card card-primary flex-fill">
                    <div class="card-header"></div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center">
                            <img src="{{ Auth::user()->adminlte_image() }}" class="img-circle shadow" width="120" height="120" alt="User Image">
                            <div class="ml-3">
                                <h3 class="md-4 text-bold">Selamat Datang,</h3>
                                <h5 class="md-4">{{ $user->nama }}</h5>
                                <h5 class="md-4"> {{ $user->username }} - {{ $mahasiswa->kelas }}</h5>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('profile') }}" class="d-flex border-top py-3 px-3 text-dark justify-content-end align-items-center card-hover">
                        Edit Profil
                        <i class="ml-1 fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

            <!-- Informasi KoTA -->
            <div class="col-md-6 d-flex">
                <div class="card card-info flex-fill">
                    <div class="card-header">
                        <h3 class="card-title"></h3>
                    </div>
                    @if($mahasiswa->status_ta === 'mahasiswa_non_ta')
                        <div class="card-body d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-graduation-cap fa-5x text-info mr-2"></i>
                                <h4 class="ml-3 text-bold">
                                    Anda belum memiliki kelompok TA! Silahkan bentuk terlebih dahulu
                                </h4>
                            </div>
                        </div>
                        <a href="{{ route('perekrutan-anggota-kota') }}" class="d-flex border-top py-3 px-3 text-dark justify-content-end align-items-center card-hover">
                            Bentuk Kelompok
                            <i class="ml-1 fas fa-arrow-right ms-2"></i>
                        </a>
                    @elseif($mahasiswa->status_ta === 'mahasiswa_ta')
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-graduation-cap fa-5x text-info mr-2"></i>
                                <div class="ml-3">
                                    <h3 class="text-bold">{{ $kotaData['kode'] ?? $kota->nama_kota ?? '-' }}</h3>
                                    <h6>{{ $kotaData['judul'] ?? $kota->judul_ta ?? 'Belum Ada Judul' }}</h6>
                                    <div class="d-flex">
                                        <div class="border border-info py-2 px-2 rounded-pill mr-1">{{ $pembimbing[0]->nama ?? '-' }}</div>
                                        <div class="border border-info py-2 px-2 rounded-pill">{{ isset($pembimbing[1]) ? $pembimbing[1]->nama : '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($kota->status_kota === 'aktif' || $kota->status_kota === 'lulus')
                        <a href="{{ route('kota.saya') }}" class="d-flex border-top py-3 px-3 text-dark justify-content-end align-items-center card-hover">
                            Detail KoTA
                            <i class="ml-1 fas fa-arrow-right ms-2"></i>
                        </a>
                        @elseif($kota->status_kota === 'pra_kota')
                        <a href="{{ url('PengajuanAlokasiPembimbing/pengajuan-pembimbing/data-kelompok') }}" class="d-flex border-top py-3 px-3 text-dark justify-content-end align-items-center card-hover">
                            Ajukan Dosen Pembimbing
                            <i class="ml-1 fas fa-arrow-right ms-2"></i>
                        </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        @if($mahasiswa->status_ta === 'mahasiswa_ta')
        <!-- Berkas Pengajuan Seminar 3 -->
        <div class="card card-warning mt-2">
            <div class="card-header">
                Berkas Pengajuan Seminar 3
            </div>
            <div class="card-body">
                <table id="artefakTable" class="table table-striped" width="100%">
                    <thead class="sticky-header">
                        <tr class="bg-dark text-white">
                            <th style="width: 80%">Nama Artefak</th>
                            <th style="width: 20%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($artefakSeminar as $item)
                            <tr>
                                <td>{{ $item['nama_artefak'] }}</td>
                                <td>
                                    @if ($item['status'] === 'Sudah diunggah')
                                        <span class="badge bg-success">{{ $item['status'] }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $item['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">Tidak ada data artefak.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Berkas Pengajuan Sidang Akhir -->
        <div class="card card-danger mt-4 mb-4">
            <div class="card-header">
                Berkas Pengajuan Sidang Akhir
            </div>
            <div class="card-body">
                <table id="artefakTable" class="table table-striped" width="100%">
                    <thead class="sticky-header">
                        <tr class="bg-dark text-white">
                            <th style="width: 80%">Nama Artefak</th>
                            <th style="width: 20%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($artefakSidang as $item)
                            <tr>
                                <td>{{ $item['nama_artefak'] }}</td>
                                <td>
                                    @if ($item['status'] === 'Sudah diunggah')
                                        <span class="badge bg-success">{{ $item['status'] }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $item['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">Tidak ada data artefak.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @elseif($user->role_user === 'dosen')
        <div class="row pt-3">
            <!-- Profil Dosen -->
            <div class="col-md-6 d-flex">
                <div class="card card-primary flex-fill">
                    <div class="card-header"></div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center">
                            <div class="d-flex flex-column align-items-center">
                                <img src="{{ Auth::user()->adminlte_image() }}" class="img-circle shadow mb-2" width="120" height="120" alt="User Image">
                                @if($dosen->bersedia_membimbing === 'bersedia')
                                <div class="border border-primary py-2 px-2 rounded-pill mr-1"> Bersedia Membimbing</div>
                                @elseif($dosen->bersedia_membimbing === 'tidak_bersedia')
                                <div class="border border-danger py-2 px-2 rounded-pill mr-1"> Belum Bersedia Membimbing</div>
                                @endif
                            </div>
                            <div class="ml-3">
                                <h4 class="md-4">Selamat Datang,</h3>
                                <h3 class="md-4 text-bold">{{ $user->nama }}</h6>
                                <h4 class="md-4"> {{ $user->username }}</h6>
                                <h6 class="md-4 text-bold"> [{{ $dosen->id_dosen }}] - [{{ $dosen->kode_dosen }}]</h6>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('profile') }}" class="d-flex border-top py-3 px-3 text-dark justify-content-end align-items-center card-hover">
                        Edit Profil
                        <i class="ml-1 fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

            <!-- Informasi Bidang -->
            <div class="col-md-6 d-flex">
                <div class="card card-info flex-fill">
                    <div class="card-header">
                        <h3 class="card-title"></h3>
                    </div>
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-graduation-cap fa-5x text-info mr-2"></i>
                                <div class="ml-3">
                                    <h3 class="text-bold">Kelompok Bidang Keahlian</h3>
                                    <h4 class="mb-4"> {{ $dosen->kbk->kbk ?? '-'}}</h6>
                                    @if($dosen->bersedia_membimbing === 'bersedia')
                                        <h3 class="text-bold">Bidang Tugas Akhir</h3>
                                        <div class="d-flex">
                                            @forelse($dosen->ketertarikanBidang as $minat)
                                                <div class="border border-info py-2 px-2 rounded-pill mr-1">{{ Str::limit($minat->bidang->bidang ?? '-', 20)}}</div>
                                            @empty
                                                Belum ada bidang yang dipilih
                                        </div> 
                                    @elseif($dosen->bersedia_membimbing === 'tidak_bersedia')
                                        Anda belum bersedia menjadi pembimbing.
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <a href="{{ url('PengajuanAlokasiPembimbing/kesediaan-membimbing/minat-bidang') }}" class="d-flex border-top py-3 px-3 text-dark justify-content-end align-items-center card-hover">
                    Formulir Kesediaan Membimbing
                    <i class="ml-1 fas fa-arrow-right ms-2"></i>
                </a>
            </div>
            </div>
        </div>

        @if($dosen->bersedia_membimbing === 'bersedia')
        <!-- Daftar KoTA -->
        <div class="card card-warning">
            <div class="card-header">
                Daftar KoTA Bimbingan
            </div>
            <div class="card-body">
                <table id="artefakTable" class="table table-striped" width="100%">
                    <thead class="sticky-header">
                        <tr class="bg-dark text-white">
                            <th style="width: 20%">KoTA</th>
                            <th style="width: 80%">Judul</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kota as $item)
                            <tr>
                                <td>{{ $item->nama_kota }}</td>
                                <td>{{ $item->judul_ta }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">Tidak ada KoTA bimbingan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @elseif($user->role_user === 'admin')
    @endif  
@stop

@section('css')
<style>
    .card-hover:hover{
        background-color: #F7F7F7;
        cursor: pointer;
    }
</style>
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
