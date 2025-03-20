@extends('adminlte::page')

@section('title', 'Data Mahasiswa')

@section('content_header')
    <h1>Data Mahasiswa</h1>
@stop

@section('content')
<button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addNewMhs">
    <i class="fa fa-plus"></i> Tambah Mahasiswa
</button>

<button class="btn btn-success mb-3" data-toggle="modal" data-target="#uploadExcel">
    <i class="fa fa-upload"></i> Upload Excel
</button>

<a href="{{ route('download.template-mhs') }}" class="btn btn-secondary mb-3">
    <i class="fa fa-download"></i> Download Template Excel
</a>
<div class="card">
    <div class="card-body">
        <table id="datatable" class="table table-borderd">
            <thead class="bg-primary text-white">
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Tahun Masuk</th>
                    <th>Prodi</th>
                    <th>Kelas</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                        $no = 1;
                    @endphp
                @foreach ($mahasiswa as $mhs)
                <tr>
                    
                    <td>{{$no}}</td>
                    <td>{{ $mhs->nim }}</td>
                    <td>{{ $mhs->nama }}</td>
                    <td>{{ $mhs->tahun_masuk }}</td>
                    <td>{{ $mhs->nama_prodi }}</td>
                    <td>{{ $mhs->kelas}}</td>
                    <td>    
                    @php
                    $no++; 
                    @endphp
                        {{-- Edit Button --}}
                            <button class="btn btn-xs btn-default text-primary mx-1 shadow btn-update-mhs" title="Edit" data-toggle="modal" data-target="#updateMhs" class="bg-purple"
                            data-nim="{{ $mhs->nim }}"
                            data-nama="{{ $mhs->nama }}"
                            data-email="{{ $mhs->email }}"
                            data-no_whatsapp="{{ $mhs->no_whatsapp }}"
                            data-kelas="{{ $mhs->kelas }}"
                            data-id_prodi="{{ $mhs->id_prodi }}"
                            data-tahun_masuk="{{ $mhs->tahun_masuk }}"
                            >
                            {{-- data-maks_bimbingan_d4="{{ $mhs->maks_bimbingan_d4 }}"
                            data-maks_bimbingan_d3="{{ $mhs->maks_bimbingan_d3 }}"> --}}
                            
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </button>
                        
        
                
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
                        {{-- <x-adminlte-modal id="deleteDosen" title="Menghapus Dosen" theme="purple" icon="fa fa-trash" size='lg' >
                            <form action="{{route('dosen.deleteDosen')}}" method="POST">
                                @csrf
                                <input type="hidden" name="nip" class="form-control" id="nip-hapus">
                                <h5>Yakin ingin menghapus dosen atas nama <b> '<span id="nama-dosen"></span>'</b></h5>
                                <button class="btn btn-danger" type="submit" >Delete</button>
                            </form>
                        </x-adminlte-modal> --}}
        
                        <x-adminlte-modal id="updateMhs" title="Mengupdate Data Mahasiswa" theme="purple"
                            icon="fa fa-plus" size='lg' >
                            <form action="{{route('mahasiswa.updateMhs')}}" method="POST">
                                @csrf
                                <div class="row">
                                        <x-adminlte-input name="nim" id="nim-update" label="NIM" placeholder="NIM"
                                            fgroup-class="col-md-6" disable-feedback  readonly   />
                                    
                                    <x-adminlte-input name="email" id="email-update" label="Email" placeholder="Email"
                                            fgroup-class="col-md-6" disable-feedback  readonly />
                                </div>
        
                                <div class="row">
                                    <x-adminlte-input name="nama" id="nama-update" label="Nama" placeholder="Nama Lengkap"
                                        fgroup-class="col-md-6" disable-feedback required/>
                                
                                        <x-adminlte-input name="no_wa" id="no_whatsapp-update" label="Nomor Whatsapp" placeholder="Nomor Whatsapp"
                                        fgroup-class="col-md-6" disable-feedback  required  />
                                    
                                 </div>

                                    <div class="row">
                                    <x-adminlte-input name="tahun_masuk" label="Tahun Masuk" id="tahun_masuk-update" placeholder="Tahun Masuk" 
                                        fgroup-class="col-md-6" maxlength="4" pattern="\d{4}" disable-feedback required/>
                                
                                        <x-adminlte-input name="kelas" label="Kelas" id="kelas-update" placeholder="Kelas"
                                        fgroup-class="col-md-6" disable-feedback required/>
                                  
                                </div>
                                
                                <div class="row">
                                    <x-adminlte-select2 name="id_prodi" label="Prodi" id="id_prodi-update" fgroup-class="col-md-6" >
                                        <option selected disabled>Pilih Prodi ....</option>
                                        @foreach($listprodi as $prodi)
                                        <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>
                                    @endforeach
                                </x-adminlte-select2>
                                </div>
        
                               
                               
                               <x-adminlte-button type="submit" label="Update" theme="primary" />
        
                               <x-slot name="footerSlot">
                                <x-adminlte-button theme="danger" label="Cancel" data-dismiss="modal"/>
                                {{-- <x-adminlte-button type="submit" label="Submit" theme="primary" /> --}}
                            </x-slot>
                            </form>
                        </x-adminlte-modal>

                       <x-adminlte-modal id="addNewMhs" title="Menambah Mahasiswa Baru" theme="purple"
                            icon="fa fa-plus" size='lg' >
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
                                    <x-adminlte-input name="tahun_masuk" label="Tahun Masuk" placeholder="Tahun Masuk .." 
                                        fgroup-class="col-md-6" maxlength="4" pattern="\d{4}" disable-feedback required/>
                                
                                        <x-adminlte-input name="kelas" label="Kelas" placeholder="Kelas"
                                        fgroup-class="col-md-6" disable-feedback required/>
                                  
                                </div>
                                
                                <div class="row">
                                    <x-adminlte-select2 name="id_prodi" label="Prodi" fgroup-class="col-md-6" required>
                                        <option selected disabled>Pilih Prodi ....</option>
                                        @foreach($listprodi as $prodi)
                                        <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>
                                    @endforeach
                                </x-adminlte-select2>

                                
                            </div>
                            <x-adminlte-button type="submit" label="Submit" theme="primary" />

                            <x-slot name="footerSlot">
                                <x-adminlte-button theme="danger" label="Cancel" data-dismiss="modal"/>
                                {{-- <x-adminlte-button type="submit" label="Submit" theme="primary" /> --}}
                            </x-slot>
                            </form>
                        </x-adminlte-modal>

                  


                  
                <x-adminlte-modal id="uploadExcel" title="Register By excel" theme="purple"
                        icon="fa fa-plus" size='lg' >

                        <form action="{{route('import-mhs')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <x-adminlte-input-file name="file" label="Upload Excel" placeholder="Choose a file..."  fgroup-class="col-md-6" disable-feedback required accept=".xls,.xlsx,.csv"/>
                            <x-adminlte-button type="submit" label="Preview" theme="primary" />
                        
                    </x-adminlte-modal>

    
    
                    </div>
</div>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    document.querySelector('input[type="file"]').addEventListener('change', function () {
        const allowedExtensions = ['xls', 'xlsx', 'csv'];
        const file = this.files[0];
        const fileExtension = file.name.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(fileExtension)) {
            alert("File harus berformat .xls, .xlsx, atau .csv!");
            this.value = ''; // Reset input file
        }
    });
    document.addEventListener("DOMContentLoaded", function () {
        const fileInput = document.querySelector('input[type="file"]');
        const label = fileInput.closest('.input-group').querySelector('.custom-file-label');

        fileInput.addEventListener('change', function () {
            if (this.files.length > 0) {
                label.textContent = this.files[0].name;
            } else {
                label.textContent = "Choose a file...";
            }
        });
    });
    $(document).ready(function () {
        $("#dosenTable").DataTable();
    });



     $(document).ready(function () {
         $(".btn-update-mhs").click(function () {
 
             var nim = $(this).data("nim");
             var nama = $(this).data("nama");
             var email = $(this).data("email");
             var no_whatsapp = $(this).data("no_whatsapp");
             var id_prodi = $(this).data("id_prodi");
             var kelas = $(this).data("kelas");
             var tahun_masuk = $(this).data("tahun_masuk");



             document.getElementById("nim-update").value = nim;
             document.getElementById("nama-update").value = nama;
             document.getElementById("email-update").value = email;
             document.getElementById("no_whatsapp-update").value = no_whatsapp;
             document.getElementById("id_prodi-update").value = id_prodi;
             document.getElementById("kelas-update").value = kelas;
             document.getElementById("tahun_masuk-update").value = tahun_masuk;

 
         });
     });

 
</script>
@stop
