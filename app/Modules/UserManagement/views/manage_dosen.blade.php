@extends('adminlte::page')

@section('title', 'Data Dosen')

@section('content_header')
    <h1>Data Dosen</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-left">
        <div>
            <!-- Button Tambah Dosen -->
            <button class="btn btn-success" data-toggle="modal" data-target="#addNewDosen">
                <i class="fa fa-plus"></i> Tambah Dosen
            </button>
            <!-- Search Input -->
            <input type="text" id="searchInput" class="form-control d-inline-block" placeholder="Cari..." style="width: 200px;">
        </div>
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
                            <button class="btn btn-xs btn-default text-primary mx-1 shadow btn-update-dosen" title="Edit" data-toggle="modal" data-target="#updateDosen" class="bg-purple"
                            data-nip="{{ $d->nip }}"
                            data-nama="{{ $d->nama }}"
                            data-role="{{ $d->role_dosen }}"
                            data-id_dosen="{{ $d->id_dosen }}"
                            data-email="{{ $d->email }}"
                            data-no_whatsapp="{{ $d->no_whatsapp }}"
                            data-kode_dosen="{{ $d->kode_dosen }}">
                            {{-- data-maks_bimbingan_d4="{{ $d->maks_bimbingan_d4 }}"
                            data-maks_bimbingan_d3="{{ $d->maks_bimbingan_d3 }}"> --}}
                            
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </button>
                        {{-- Delete Button --}}
                        {{-- @if($d->role_dosen != 'kajur')
                            <button class="btn btn-xs btn-default text-danger mx-1 shadow btn-delete-dosen" title="Delete"data-toggle="modal" data-target="#deleteDosen" class="bg-purple"
                            data-nip="{{ $d->nip }}"
                            data-nama="{{ $d->nama}}">
                                <i class="fa fa-lg fa-fw fa-trash"></i>
                            </button>
                        @endif --}}
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

                

            </tbody>
        </table>
                        {{-- <x-adminlte-modal id="deleteDosen" title="Menghapus Dosen" theme="purple" icon="fa fa-trash" size='lg' disable-animations>
                            <form action="{{route('dosen.deleteDosen')}}" method="POST">
                                @csrf
                                <input type="hidden" name="nip" class="form-control" id="nip-hapus">
                                <h5>Yakin ingin menghapus dosen atas nama <b> '<span id="nama-dosen"></span>'</b></h5>
                                <button class="btn btn-danger" type="submit" >Delete</button>
                            </form>
                        </x-adminlte-modal> --}}
        
                        <x-adminlte-modal id="updateDosen" title="Mengupdate Data Dosen" theme="purple"
                            icon="fa fa-plus" size='lg' disable-animations>
                            <form action="{{route('dosen.updateDosen')}}" method="POST">
                                @csrf
                                <div class="row">
                                        <x-adminlte-input name="nip" id="nip-update" label="NIP" placeholder="NIP"
                                            fgroup-class="col-md-6" disable-feedback  readonly   />
                                    
                                    <x-adminlte-input name="email" id="email-update" label="Email" placeholder="Email"
                                            fgroup-class="col-md-6" disable-feedback  required  />
                                </div>
        
                                <div class="row">
                                    <x-adminlte-input name="nama" id="nama-update" label="Nama" placeholder="Nama Lengkap"
                                        fgroup-class="col-md-6" disable-feedback required/>
                                
        
                                    <x-adminlte-input name="id" id="id_dosen-update" label="ID" placeholder="ID"
                                        fgroup-class="col-md-6" disable-feedback  required  />
                                 </div>
                                <div class="row">
                                    <x-adminlte-input name="no_wa" id="no_whatsapp-update" label="Nomor Whatsapp" placeholder="Nomor Whatsapp"
                                               fgroup-class="col-md-6" disable-feedback  required  />
                                     <x-adminlte-input name="kode" label="Kode" id="kode_dosen-update" placeholder="Kode"
                                               fgroup-class="col-md-6" disable-feedback  required  />
                                </div>
        
                                <hr>
                                <h4>Bimbingan</h4>
                                <div class="row">
                                    <x-adminlte-input name="max_d4" label="Jumlah Bimbingan Maksimal (D4)" placeholder="..."
                                              fgroup-class="col-md-6" disable-feedback  type="number"  min="0" max="5" id="maks_bimbingan_d4-update" />
                                    <x-adminlte-input name="max_d3" label="Jumlah Bimbingan Maksimal (D3)" placeholder="..."
                                              fgroup-class="col-md-6" disable-feedback  type="number"  min="0" max="5" id="maks_bimbingan_d3-update"  />
                               </div>
                               <x-adminlte-button type="submit" label="Update" theme="primary" />
        
                               <x-slot name="footerSlot">
                                <x-adminlte-button theme="danger" label="Cancel" data-dismiss="modal"/>
                                {{-- <x-adminlte-button type="submit" label="Submit" theme="primary" /> --}}
                            </x-slot>
                            </form>
                        </x-adminlte-modal>

                        <x-adminlte-modal id="changeRole" title="Manajemen Role Dosen" theme="purple" icon="fas fa-key" size='lg' disable-animations>
                            
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
                                <div class="row">
                                    <x-adminlte-select2 name="id_kbk" label="KBK" fgroup-class="col-md-6" >
                                        <option selected disabled>Pilih KBK ....</option>
                                        @foreach($listkbk as $kbk)
                                        <option value="{{ $kbk->id_kbk }}">{{ $kbk->kbk }}</option>
                                    @endforeach
                                </x-adminlte-select2>

                                <x-adminlte-select2 name="status_dosen" label="Status" fgroup-class="col-md-6">
                                    <option selected disabled>Pilih Status ....</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Non Aktif</option>

                            </x-adminlte-select2>
                        </div>

                                {{-- <hr>
                                <h4>Bimbingan</h4>
                                <div class="row">
                                    <x-adminlte-input name="max_d4" label="Jumlah Bimbingan Maksimal (D4)" placeholder="..."
                                            fgroup-class="col-md-6" disable-feedback  type="number"  min="0" max="5"/>
                                    <x-adminlte-input name="max_d3" label="Jumlah Bimbingan Maksimal (D3)" placeholder="..."
                                            fgroup-class="col-md-6" disable-feedback  type="number"  min="0" max="5" required  />
                            </div> --}}
                            <x-adminlte-button type="submit" label="Submit" theme="primary" />

                            <x-slot name="footerSlot">
                                <x-adminlte-button theme="danger" label="Cancel" data-dismiss="modal"/>
                                {{-- <x-adminlte-button type="submit" label="Submit" theme="primary" /> --}}
                            </x-slot>
                            </form>
                        </x-adminlte-modal>


               
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
    $(document).ready(function () {
         $(".btn-delete-dosen").click(function () {
             var nip = $(this).data("nip");
             var nama = $(this).data("nama");
 
             document.getElementById("nip-hapus").value = nip;
             $("#nama-dosen").text(nama);
         });
     });
 
     $(document).ready(function () {
         $(".btn-update-dosen").click(function () {
 
             var nip = $(this).data("nip");
             var nama = $(this).data("nama");
             var email = $(this).data("email");
             var no_whatsapp = $(this).data("no_whatsapp");
             var id_dosen = $(this).data("id_dosen");
             var kode_dosen = $(this).data("kode_dosen");
             var maks_bimbingan_d4 = $(this).data("maks_bimbingan_d4");
             var maks_bimbingan_d3 = $(this).data("maks_bimbingan_d3");
 
             document.getElementById("nip-update").value = nip;
             document.getElementById("nama-update").value = nama;
             document.getElementById("email-update").value = email;
             document.getElementById("no_whatsapp-update").value = no_whatsapp;
             document.getElementById("id_dosen-update").value = id_dosen;
             document.getElementById("kode_dosen-update").value = kode_dosen;
             document.getElementById("maks_bimbingan_d4-update").value = maks_bimbingan_d4;
             document.getElementById("maks_bimbingan_d3-update").value = maks_bimbingan_d3;
 
         });
     });
</script>
@stop
