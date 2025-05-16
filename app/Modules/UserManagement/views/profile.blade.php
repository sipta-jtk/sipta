@extends('adminlte::page')

@section('title', 'Profile')

@section('content_header')
    <h1 class="mb-3">Profil Saya</h1>

    <div>
        @component('UserManagement.components.breadcrumb', [
            'links' => [
                ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Profil'],
            ]
        ])
        @endcomponent
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Foto Profil -->
                <div class="text-center mb-4">
                    <img src="{{ Auth::user()->adminlte_image() }}" class="rounded-circle" width="250" height="250" alt="Profile Image">
                    <input type="file" name="photo" class="form-control mt-2">
                    <small>Ukuran maksimum 2MB, dengan format PNG atau JPG</small>
                </div>

                <!-- Formulir Data Umum -->
                <div class="row px-3">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6 mb-2">
                        <p class="text-secondary text-md border-bottom">Identitas</p>

                        <label>Nama</label>
                        <x-adminlte-input name="nama" type="text" value="{{ old('nama', Auth::user()->nama) }}" required pattern="[A-Za-z\s]+" title="Hanya huruf dan spasi diperbolehkan" />

                        @if(Auth::user()->role_user === 'mahasiswa' && Auth::user()->mahasiswa)
                            <label>NIM</label>
                            <x-adminlte-input name="nim" value="{{ Auth::user()->mahasiswa->nim }}" readonly />

                            <label>Kelas</label>
                            <x-adminlte-input name="kelas" value="{{ Auth::user()->mahasiswa->kelas }}" readonly />

                            <label>Tahun Masuk</label>
                            <x-adminlte-input name="tahun_masuk" value="{{ Auth::user()->mahasiswa->tahun_masuk }}" readonly />

                            <label>Program Studi</label>
                            <x-adminlte-input name="prodi" value="{{ Auth::user()->mahasiswa->prodi->nama_prodi }}" readonly />
                        @endif

                        @if(Auth::user()->role_user === 'dosen' && Auth::user()->dosen)
                            <label>NIP</label>
                            <x-adminlte-input name="nip" value="{{ Auth::user()->dosen->nip }}" readonly />

                            <label>KBK</label>
                            <x-adminlte-input name="kbk" value="{{ Auth::user()->dosen->kbk->kbk }}" readonly />

                            <label>Role</label>
                            <x-adminlte-input name="role_dosen" value="{{ Auth::user()->dosen->role_dosen }}" readonly />
                        @endif
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6 mb-2">
                        <p class="text-secondary text-md border-bottom">Kontak & Status</p>

                        <label>Nomor Telepon</label>
                        <x-adminlte-input name="no_whatsapp" type="number" value="{{ old('no_whatsapp', Auth::user()->no_whatsapp) }}" />

                        <label>Email</label>
                        <x-adminlte-input name="email" value="{{ old('email', Auth::user()->email) }}" readonly />

                        @if(Auth::user()->role_user === 'mahasiswa' && Auth::user()->mahasiswa)
                            <label>Status</label>
                            <x-adminlte-input name="status_ta" value="{{ Auth::user()->mahasiswa->status_ta }}" readonly />
                        @endif

                        @if(Auth::user()->role_user === 'dosen' && Auth::user()->dosen)
                            <label>Status</label>
                            <x-adminlte-input name="status_dosen" value="{{ Auth::user()->dosen->status_dosen }}" readonly />
                        @endif
                    </div>
                </div>

                <div class="mt-3 text-right">
                    <button type="submit" class="btn btn-success ">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@stop
