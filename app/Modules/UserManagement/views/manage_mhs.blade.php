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
            <button class="btn btn-success" data-toggle="modal" data-target="#addNewMhs">
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
                    <td>
                        @if ($mhs->status_ta == 'mahasiswa_ta')
                            Mahasiswa TA
                        @else 
                            Mahasiswa
                       @endif
                    </td>

                    <td>    

                        {{-- Edit Button --}}
                            <button class="btn btn-xs btn-default text-primary mx-1 shadow btn-update-dosen" title="Edit" data-toggle="modal" data-target="#updateMhs" class="bg-purple"
                            data-nip="{{ $mhs->nim }}"
                            data-nama="{{ $mhs->nama }}"
                            data-email="{{ $mhs->email }}"
                            data-no_whatsapp="{{ $mhs->no_whatsapp }}">
                            {{-- data-maks_bimbingan_d4="{{ $mhs->maks_bimbingan_d4 }}"
                            data-maks_bimbingan_d3="{{ $mhs->maks_bimbingan_d3 }}"> --}}
                            
                                <i class="fa fa-lg fa-fw fa-pen"></i>
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
        
                        <x-adminlte-modal id="updateMhs" title="Mengupdate Data Mahasiswa" theme="purple"
                            icon="fa fa-plus" size='lg' disable-animations>
                            <form action="" method="POST">
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
                                     
                                </div>
        
                                <hr>
                                <h4>Bimbingan</h4>
                               
                               <x-adminlte-button type="submit" label="Update" theme="primary" />
        
                               <x-slot name="footerSlot">
                                <x-adminlte-button theme="danger" label="Cancel" data-dismiss="modal"/>
                                {{-- <x-adminlte-button type="submit" label="Submit" theme="primary" /> --}}
                            </x-slot>
                            </form>
                        </x-adminlte-modal>

                       <x-adminlte-modal id="addNewMhs" title="Menambah Mahasiswa Baru" theme="purple"
                            icon="fa fa-plus" size='lg' disable-animations>
                            <form action="{{route('mahasiswa.addNewMhs')}}" method="POST">
                                @csrf
                                <div class="row">
                                        <x-adminlte-input name="nim" label="NIM" placeholder="NIM"
                                            fgroup-class="col-md-6" disable-feedback  required  />
                                    

                                    <x-adminlte-input name="email" label="Email" placeholder="Email"
                                            fgroup-class="col-md-6" disable-feedback  required  />
                                </div>

                                <div class="row">
                                    <x-adminlte-input name="nama" label="Nama" placeholder="Nama Lengkap"
                                        fgroup-class="col-md-6" disable-feedback required/>
                                
                                    <x-adminlte-input name="no_wa" label="Nomor Whatsapp" placeholder="Nomor Whatsapp"
                                        fgroup-class="col-md-6" disable-feedback  required  />
                                  
                                </div>
                                
                                <div class="row">
                                    <x-adminlte-select2 name="id_prodi" label="Prodi" fgroup-class="col-md-6" >
                                        <option selected disabled>Pilih Prodi ....</option>
                                        @foreach($listprodi as $prodi)
                                        <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>
                                    @endforeach
                                </x-adminlte-select2>

                                <x-adminlte-select2 name="status_dosen" label="Status" fgroup-class="col-md-6">
                                    <option selected disabled>Pilih Status ....</option>
                                    <option value="mahasiswa_ta">Mahasiswa TA</option>
                                    <option value="mahasiswa_non_ta">Mahasiswa</option>

                            </x-adminlte-select2>
                            </div>
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
             var status_dosen = $(this).data("status_dosen");


             document.getElementById("nip-update").value = nip;
             document.getElementById("nama-update").value = nama;
             document.getElementById("email-update").value = email;
             document.getElementById("no_whatsapp-update").value = no_whatsapp;
             document.getElementById("id_dosen-update").value = id_dosen;
             document.getElementById("kode_dosen-update").value = kode_dosen;
             document.getElementById("status_dosen-update").value = status_dosen;

 
         });
     });

 
</script>
@stop
