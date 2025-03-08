@extends('adminlte::page')

@section('title', 'Data Dosen')

@section('content_header')
    <h1>Data Dosen</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">List Dosen</h3>
    </div>
    <div class="card-body">
        <table id="dosenTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No WhatsApp</th>
                    <th>Status Dosen</th>
                    <th>Role Dosen</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dosen as $d)
                <tr>
                    <td>{{ $d->nip }}</td>
                    <td>{{ $d->nama }}</td>
                    <td>{{ $d->email }}</td>
                    <td>{{ $d->no_whatsapp }}</td>
                    {{-- <td>{{ $d->maks_bimbingan_d4 }}</td>
                    <td>{{ $d->maks_bimbingan_d3 }}</td> --}}
                    <td>{{ $d->status_dosen }}</td>
                    <td>
                        @if ($d->role_dosen == 'koordinator_ta')
                            Koordinator TA
                        @elseif ($d->role_dosen == 'kajur')
                            Ketua Jurusan
                        @else
                            Dosen
                        @endif
                    </td>

                    <td>    

                        {{-- Edit Button --}}
                            <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </button>
                        {{-- Delete Button --}}
                            <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                                <i class="fa fa-lg fa-fw fa-trash"></i>
                            </button>
                        {{-- Management Role Button --}}
                            <button class="btn btn-xs btn-default text-teal mx-1 shadow btn-change-role" title="Keys" data-toggle="modal" data-target="#changeRole" class="bg-purple"
                            data-nip="{{ $d->nip }}"
                            data-nama="{{ $d->nama }}"
                            data-role="{{ $d->role_dosen }}">
                                <i class="fa fa-lg fa-fw fa-key"></i>
                            </button>
                
                    </td>
                </tr>
                @endforeach
                    <x-adminlte-modal id="changeRole" title="Manajemen Role Dosen" theme="purple"
                    icon="fas fa-key" size='lg' disable-animations>
                    <form action="{{route('dosen.update_role')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" name="nip" class="form-control" id="nip-input" placeholder="NIP" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" id="nama-input" placeholder="Nama" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role Saat Ini</label>
                            <select class="form-control" id="role-input" name="role">
                                <option value="dosen">Dosen</option>
                                <option value="koordinator_ta">Koordinator TA</option>
                                <option value="kajur">Ketua Jurusan</option>
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit" >Update</button>

                    </form>

                    {{-- <p><strong>NIP:</strong> <span id="modal-nip"></span></p>
                    <p><strong>Nama:</strong> <span id="modal-nama"></span></p>
                    <p><strong>Role Saat Ini:</strong> <span id="modal-role"></span></p> --}}
                </x-adminlte-modal>
            </tbody>
        </table>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function () {
        $("#dosenTable").DataTable();
    });

    $(document).ready(function () {
        $(".btn-change-role").click(function () {
            var nip = $(this).data("nip");
            var nama = $(this).data("nama");
            var role = $(this).data("role");

            document.getElementById("nip-input").value = nip;
            document.getElementById("nama-input").value = nama;
            document.getElementById("role-input").value = role;
        });
    });
</script>
@stop
