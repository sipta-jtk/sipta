@extends('adminlte::page')

@section('title', 'Profile')

@section('content_header')
    <h1>Profil Saya</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Foto Profil -->
                <div class="text-center mb-3">
                <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('storage/photos/default-profile.jpg') }}" class="rounded-circle" width="250" height="250" alt="Profile Image">
                    <input type="file" name="photo" class="form-control mt-2">
                    <small>Ukuran maksimum 2MB, dengan format PNG atau JPG</small>
                </div>

                <!-- Data Umum -->
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', Auth::user()->nama) }}">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" readonly>
                </div>

                <!-- Jika User adalah Mahasiswa -->
                @if(Auth::user()->role_user === 'mahasiswa' && Auth::user()->mahasiswa)
                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->mahasiswa->nim }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Kelas</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->mahasiswa->kelas }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Tahun Masuk</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->mahasiswa->tahun_masuk }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Program Studi</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->mahasiswa->prodi->nama_prodi }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <input type="text" name="no_whatsapp" class="form-control" value="{{ old('no_whatsapp', Auth::user()->no_whatsapp) }}">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->mahasiswa->status_ta }}" readonly>
                    </div>
                @endif

                <!-- Jika User adalah Dosen -->
                @if(Auth::user()->role_user === 'dosen' && Auth::user()->dosen)
                    <div class="form-group">
                        <label>NIP</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->dosen->nip }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>KBK</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->dosen->kbk->kbk }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->dosen->role_dosen }}" readonly>

                    </div>

                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <input type="text" name="no_whatsapp" class="form-control" value="{{ old('no_whatsapp', Auth::user()->no_whatsapp) }}">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->dosen->status_dosen }}" readonly>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@stop
