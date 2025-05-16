@extends('adminlte::page')

@section('title', 'Preview Data Dosen')

@section('content_header')
    <h1>Data Import Dosen</h1>
@stop
@section('content')
<a href="{{route('manage.dosen')}}" class="btn btn-primary mb-3">
    <i class="fa fa-back"></i> Kembali
</a>
<div class="card">
    <div class="card-body">

        @if (session('importedData'))
        <form action="{{route('inputBulkDosen')}}" method="POST">
            @csrf
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10%">NIP</th>
                        <th  style="width: 20%">Nama</th>
                        <th style="width: 15%">Email</th>
                        <th style="width: 10%">ID </th>
                        <th style="width: 10%">Kode</th>
                        <th  style="width: 10%">No. WA</th>
                        <th style="width: 15%">KBK</th>
                        <th  style="width: 10%">Status</th>
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
                                <input type="text" name="data[{{ $index }}][id_dosen]" value="{{ $row['id_dosen'] }}" class="form-control" required>
                            </td>
                            <td>
                                <input type="text" name="data[{{ $index }}][kode_dosen]" value="{{ $row['kode_dosen'] }}" class="form-control" required>
                            </td>
                            <td>
                                <input type="text" name="data[{{ $index }}][no_wa]" value="{{ $row['no_wa'] }}" class="form-control" required>
                            </td>
                            <td>
                                <x-adminlte-select2 name="data[{{ $index }}][id_kbk]" fgroup-class="col-md-8" required>
                                    <option selected disabled>Pilih KBK ....</option>
                                    @foreach($kbk as $k)
                                    <option value="{{$k->id_kbk}}">{{ $k->kbk }}</option>
                                @endforeach
                            </x-adminlte-select2>
                            </td>
                            <td>
                                @if($row['emailExist'])
                                    <span class="badge bg-danger">Email Sudah Ada</span>
                                @endif
                                @if($row['usernameExist'])
                                    <span class="badge bg-danger">Username Sudah Ada</span>
                                @endif
                                @if($row['kodeExist'])
                                    <span class="badge bg-danger">Kode Sudah Ada</span>
                                @endif
                                @if($row['idExist'])
                                    <span class="badge bg-danger">Id Sudah Ada</span>
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
