@extends('adminlte::page')

@section('title', 'Preview Data Mahasiswa')

@section('content_header')
    <h1>Data Import Mahasiswa</h1>
@stop
@section('content')
<a href="{{route('manage.mhs')}}" class="btn btn-primary mb-3">
    <i class="fa fa-back"></i> Kembali
</a>
<div class="card">
    <div class="card-body">

        @if (session('importedData'))
        <form action="{{route('inputBulkMhs')}}" method="POST">
            @csrf
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10%">NIM</th>
                        <th  style="width: 20%">Nama</th>
                        <th style="width: 15%">Email</th>
                        <th style="width: 10%">Tahun Masuk</th>
                        <th style="width: 10%">Kelas</th>
                        <th  style="width: 10%">No. WA</th>
                        <th style="width: 15%">Prodi</th>
                        <th  style="width: 10%">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (session('importedData') as $index => $row)
                        <tr>
                            <td>
                                <input type="text" name="data[{{ $index }}][username]" value="{{ $row['username'] }}" class="form-control" required>
                            </td>
                            <td>
                                <input type="text" name="data[{{ $index }}][nama]" value="{{ $row['nama'] }}" class="form-control" required>
                            </td>
                            <td>
                                <input type="email" name="data[{{ $index }}][email]" value="{{ $row['email'] }}" class="form-control" required>
                            </td>
                            <td>
                                <input type="number" name="data[{{ $index }}][tahun_masuk]" value="{{ $row['tahun_masuk'] }}" class="form-control" required>
                            </td>
                            <td>
                                <input type="text" name="data[{{ $index }}][kelas]" value="{{ $row['kelas'] }}" class="form-control" required>
                            </td>
                            <td>
                                <input type="text" name="data[{{ $index }}][no_wa]" value="{{ $row['no_wa'] }}" class="form-control" required>
                            </td>
                            <td>
                                <x-adminlte-select2 name="data[{{ $index }}][id_prodi]" fgroup-class="col-md-8" required>
                                    <option selected disabled>Pilih Prodi ....</option>
                                    @foreach($prodi as $prod)
                                    <option value="{{$prod->id_prodi}}">{{ $prod->nama_prodi }}</option>
                                @endforeach
                            </x-adminlte-select2>
                            </td>
                            <td>
                                @if($row['emailExist'])
                                    <span class="badge bg-danger">Email Sudah Ada</span>
                                @endif
                                @if($row['usernameExist'])
                                    <span class="badge bg-danger">NIM Sudah Ada</span>
                                @endif
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
