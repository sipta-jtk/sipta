@extends('adminlte::page')

@section('title', 'Data Dosen')

@section('content_header')
    <h1>Data Mahasiswa</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-left">
        <div>
            <!-- Button Tambah Dosen -->
            <button class="btn btn-success" data-toggle="modal" data-target="#addNewDosen">
                <i class="fa fa-plus"></i> Tambah Mahasiswa
            </button>
            <!-- Search Input -->
            <input type="text" id="searchInput" class="form-control d-inline-block" placeholder="Cari..." style="width: 200px;">
        </div>
    </div>
    <div class="card-body">
        <table id="dosenTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No WhatsApp</th>
                    <th>Status Mahasiswa</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mahasiswa as $mhs)
                <tr>
                    <td>{{ $mhs->nim }}</td>
                    <td>{{ $mhs->nama }}</td>
                    <td>{{ $mhs->email }}</td>
                    <td>{{ $mhs->no_whatsapp }}</td>
                    {{-- <td>{{ $mhs->status_ta }}</td> --}}
                    <td>
                        @if ($mhs->status_ta == 'mahasiswa_ta')
                            Mahasiswa TA
                        @else 
                            Mahasiswa
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
                            data-nip="{{ $mhs->nim }}"
                            data-nama="{{ $mhs->nama }}"
                            data-role="{{ $mhs->status_ta }}">
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
                            <label for="nip" class="form-label">NIM</label>
                            <input type="text" name="nip" class="form-control" id="nip-input" placeholder="NIP" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" id="nama-input" placeholder="Nama" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role Saat Ini</label>
                            <select class="form-control" id="role-input" name="role">
                                <option value="mahasiswa_ta">Mahasiswa TA</option>
                                <option value="mahasiswa_non_ta">Mahasiswa Non TA</option>
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit" >Update</button>

                    </form>
                </x-adminlte-modal>

                <x-adminlte-modal id="addNewDosen" title="Menambah Dosen Baru" theme="purple"
                    icon="fa fa-plus" size='lg' disable-animations>
                    <form action="{{route('dosen.add_new_dosen')}}" method="POST">
                        @csrf
                        <div class="row">
                                <x-adminlte-input name="nip" label="NIP" placeholder="NIP"
                                    fgroup-class="col-md-6" disable-feedback  required  />
                            

                            <x-adminlte-input name="email" label="Email" placeholder="Email"
                                    fgroup-class="col-md-6" disable-feedback  required  />
                        </div>

                        <div class="row">
                            <x-adminlte-input name="nama" label="Nama" placeholder="Nama Lengkap"
                                fgroup-class="col-md-6" disable-feedback required/>
                        

                            <x-adminlte-input name="id" label="ID" placeholder="ID"
                                fgroup-class="col-md-6" disable-feedback  required  />
                         </div>
                        <div class="row">
                            <x-adminlte-input name="no_wa" label="Nomor Whatsapp" placeholder="Nomor Whatsapp"
                                       fgroup-class="col-md-6" disable-feedback  required  />
                             <x-adminlte-input name="kode" label="Kode" placeholder="Kode"
                                       fgroup-class="col-md-6" disable-feedback  required  />
                        </div>

                        <hr>
                        <h4>Bimbingan</h4>
                        <div class="row">
                            <x-adminlte-input name="max_d4" label="Jumlah Bimbingan Maksimal (D4)" placeholder="..."
                                      fgroup-class="col-md-6" disable-feedback  type="number"  min="0" max="5"/>
                            <x-adminlte-input name="max_d3" label="Jumlah Bimbingan Maksimal (D3)" placeholder="..."
                                      fgroup-class="col-md-6" disable-feedback  type="number"  min="0" max="5" required  />
                       </div>
                       <x-adminlte-button type="submit" label="Submit" theme="primary" />

                       <x-slot name="footerSlot">
                        <x-adminlte-button theme="danger" label="Cancel" data-dismiss="modal"/>
                        {{-- <x-adminlte-button type="submit" label="Submit" theme="primary" /> --}}
                    </x-slot>
                    </form>
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
