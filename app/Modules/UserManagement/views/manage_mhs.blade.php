@extends('adminlte::page')
@section('title', 'Data Mahasiswa')
@section('content_header')
    <h1>Data Mahasiswa</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
        'links' => [
        ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
        ['url' => url(), 'label' => 'Manajemen Akun Mahasiswa']
        ]])
        @endcomponent
        </div>
@stop
@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3" style="gap: 0.5rem;">
            <button class="btn btn-primary" data-toggle="modal" data-target="#uploadExcel">
                <i class="fa fa-upload"></i> Unggah Excel
            </button>
            <a href="{{ route('download.template-mhs') }}" class="btn btn-primary">
                <i class="fa fa-download"></i> Unduh Format Excel
            </a>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addNewMhs">
                <i class="fa fa-plus"></i> Tambah Mahasiswa
            </button>
        </div>
        <div id="role_section" style="display: none;">
            <div class="mb-3">
                <label for="new_role">Pilih Peran Baru:</label>
                <select id="new_role" class="form-control">
                    <option value="">-- Pilih Peran --</option>
                    <option value="dosen">Dosen</option>
                    <option value="koordinator_ta">Koordinator TA</option>
                </select>
            </div>
            <div class="d-flex justify-content-end mb-3" style="gap: 0.5rem;">
            <button id="updateRoleButton" class="btn btn-success">Ubah Peran</button>
            </div>
        </div>
        <table id="datatable" class="table table-striped">
            <thead class="bg-dark text-white">
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Prodi</th>
                    <th>Kelas</th>
                    <th>Tahun Masuk</th>
                    <th>Aksi</th>
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
                    <td>{{ $mhs->nama_prodi }}</td>
                    <td>{{ $mhs->kelas}}</td>
                    <td>{{ $mhs->tahun_masuk }}</td>
                    <td>    
                    @php
                    $no++; 
                    @endphp
                    @if($mhs->status_user != 'nonaktif')
                        <button class="btn btn-danger btn-xs shadow btn-nonaktif-mhs" title="Nonaktifkan Mahasiswa" data-toggle="modal" data-target="#nonAktifMhs" 
                        data-nim="{{ $mhs->nim }}"
                        data-nama="{{ $mhs->nama }}">
                        <i class="fas fa-power-off m-1"></i>
                    </button>
                    @else
                    <button class="btn btn-success btn-xs shadow btn-aktif-mhs" title="Aktifkan Mahasiswa" data-toggle="modal" data-target="#AktifMhs" 
                        data-nim="{{ $mhs->nim }}"
                        data-nama="{{ $mhs->nama }}">
                        <i class="fas fa-power-off m-1"></i> 
                    </button>
                    @endif
                            <button class="btn btn-warning btn-xs me-2 shadow btn-update-mhs" title="Edit" data-toggle="modal" data-target="#updateMhs"
                            data-nim="{{ $mhs->nim }}"
                            data-nama="{{ $mhs->nama }}"
                            data-email="{{ $mhs->email }}"
                            data-no_whatsapp="{{ $mhs->no_whatsapp }}"
                            data-kelas="{{ $mhs->kelas }}"
                            data-id_prodi="{{ $mhs->id_prodi }}"
                            data-tahun_masuk="{{ $mhs->tahun_masuk }}"
                            >
                                <i class="fas fa-edit"></i>
                            </button>
                            
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
                        
        
                        <x-adminlte-modal id="updateMhs" title="Mengubah Data Mahasiswa" theme="blue" size='lg' >
                            <form action="{{route('mahasiswa.updateMhs')}}" method="POST">
                                @csrf
                                <p class="text-secondary text-md border-bottom">Identitas Pribadi</p>

                                    <x-adminlte-input name="nama" pattern="[A-Za-z\s.,]+" id="nama-update" label="Nama" placeholder="Nama Lengkap"
                                    fgroup-class="" disable-feedback required/>

                                    <x-adminlte-input name="nim" id="nim-update" label="NIM" placeholder="NIM"
                                            fgroup-class="" disable-feedback  readonly   />
                                   
        

                                    <x-adminlte-input name="tahun_masuk" label="Tahun Masuk" id="tahun_masuk-update" placeholder="Tahun Masuk" 
                                        fgroup-class="" maxlength="4" pattern="\d{4}" disable-feedback required/>
                                
                                        <x-adminlte-input name="kelas" label="Kelas" id="kelas-update" placeholder="Kelas"
                                        fgroup-class="" disable-feedback required/>
                                                                  
                                    <x-adminlte-select2 name="id_prodi" label="Prodi" id="id_prodi-update" fgroup-class="" >
                                        <option selected disabled>Pilih Prodi ....</option>
                                        @foreach($listprodi as $prodi)
                                        <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>
                                    @endforeach
                                </x-adminlte-select2>
                                
                                <p class="text-secondary text-md border-bottom">Kontak</p>

                                        
                                    <x-adminlte-input name="no_wa" id="no_whatsapp-update" label="Nomor Whatsapp" placeholder="Nomor Whatsapp"
                                    fgroup-class="" disable-feedback  required  />

                                    <x-adminlte-input name="email" id="email-update" label="Email" placeholder="Email"
                                    fgroup-class="" disable-feedback  readonly />
                            
                               
                               
                                <div class="d-flex pt-3 justify-content-end">
                                    <x-adminlte-button theme="danger" label="Tutup"
                                    data-dismiss="modal" class="mx-1"/>
                                    <x-adminlte-button theme="success" label="Simpan"
                                     class="mx-1" type="submit"/>
                                    </div>
                                    <x-slot name="footerSlot"></x-slot>
                            </form>
                        </x-adminlte-modal>

                       <x-adminlte-modal id="addNewMhs" title="Menambah Mahasiswa Baru" theme="blue" size='lg' >
                            <form action="{{route('mahasiswa.addNewMhs')}}" method="POST">
                                @csrf
                                <p class="text-secondary text-md border-bottom">Identitas Pribadi</p>
                                    <x-adminlte-input name="nama" pattern="[A-Za-z\s.,]+" label="Nama" placeholder="Nama Lengkap"
                                        fgroup-class="" disable-feedback required/>
                                
                                    <x-adminlte-input name="nim" label="NIM" placeholder="NIM"
                                        fgroup-class="" disable-feedback  required  />

                                    <x-adminlte-input name="tahun_masuk" label="Tahun Masuk" placeholder="Tahun Masuk .." 
                                        fgroup-class="" maxlength="4" pattern="\d{4}" disable-feedback required/>
                                
                                        <x-adminlte-input name="kelas" label="Kelas" placeholder="Kelas"
                                        fgroup-class="" disable-feedback required/>
                                  
                                    <x-adminlte-select2 name="id_prodi" label="Prodi" fgroup-class="" required>
                                        <option selected disabled>-Pilih Prodi-</option>
                                        @foreach($listprodi as $prodi)
                                        <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>
                                    @endforeach
                                </x-adminlte-select2>
                                <p class="text-secondary text-md border-bottom">Kontak</p>
                                    <x-adminlte-input name="no_wa" label="Nomor Whatsapp" placeholder="Nomor Whatsapp"
                                        fgroup-class="" disable-feedback  required  />
                                    
                                        <x-adminlte-input name="email" label="Email" placeholder="Email"
                                        fgroup-class="" disable-feedback  required  />

                            <div class="d-flex pt-3 justify-content-end">
                                <x-adminlte-button theme="danger" label="Tutup"
                                data-dismiss="modal" class="mx-1"/>
                                <x-adminlte-button theme="success" label="Simpan"
                                 class="mx-1" type="submit"/>
                                </div>
                                   <x-slot name="footerSlot"></x-slot>
                        </form>
                        </x-adminlte-modal>
                  
                <x-adminlte-modal id="uploadExcel" title="Tambah Akun Melalui Excel" theme="blue" size='lg' >
                        <form action="{{route('import-mhs')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <x-adminlte-input-file name="file" label="Upload Excel" placeholder="Pilih file excel ..."  fgroup-class="" disable-feedback required accept=".xls,.xlsx,.csv"/>
                            <div class="d-flex pt-3 justify-content-end">
                                <x-adminlte-button theme="danger" label="Tutup"
                                data-dismiss="modal" class="mx-1"/>
                                <x-adminlte-button theme="success" label="Pratinjau"
                                 class="mx-1" type="submit"/>
                                </div>
                                <x-slot name="footerSlot"></x-slot>
                        </form>
                </x-adminlte-modal>

                <x-adminlte-modal id="nonAktifMhs" title="Menonaktifkan Mahasiswa" theme="blue" size='lg'>
                            <form action="{{route('nonaktif-mhs')}}" method="POST">
                                @csrf
                                <h4 id="konfirmasi-pesan-nonaktif">Yakin ingin menonaktifkan Mahasiswa ?</h4>
                                <input type="hidden" name="nim" class="form-control" id="nim-input-nonaktif">
                                <div class="d-flex justify-content-end">
                                    <x-adminlte-button theme="success" label="Nonaktifkan"
                                     class="mx-1" type="submit"/>
                                    </div>
                                    <x-slot name="footerSlot"></x-slot>
                            </form>
                    </x-adminlte-modal>
                    <x-adminlte-modal id="AktifMhs" title="Aktifkan Mahasiswa" theme="blue" size='lg'>
                            <form action="{{route('aktif-mhs')}}" method="POST" >
                                @csrf
                                <h4 id="konfirmasi-pesan-aktif">Yakin ingin mengaktifkan Mahasiswa ?</h4>
                                <input type="hidden" name="nim" class="form-control" id="nim-input-aktif">
                                <div class="d-flex justify-content-end">
                                    <x-adminlte-button theme="success" label="Aktifkan"
                                     class="mx-1" type="submit"/>
                                    </div>
                                    <x-slot name="footerSlot"></x-slot>
                            </form>
                    </x-adminlte-modal>  
            </div>
            </div>


@stop

@section('css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">

@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>


<script>
  

    document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.querySelector('input[type="file"]');
    if (!fileInput) return;

    const allowedExtensions = ['xls', 'xlsx', 'csv'];

    fileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const fileExtension = file.name.split('.').pop().toLowerCase();
        if (!allowedExtensions.includes(fileExtension)) {
            alert("File harus berformat .xls, .xlsx, atau .csv!");
            this.value = ''; // Reset input file
        }

        const label = fileInput.closest('.input-group')?.querySelector('.custom-file-label');
        if (label) {
            label.textContent = file.name;
        }
    });
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

             console.log(nim);
 
         });
     });

     $(document).ready(function () {
        $(".btn-nonaktif-mhs").click(function () {
            var nim = $(this).data("nim");
            var nama = $(this).data("nama");
            document.getElementById("nim-input-nonaktif").value = nim;
            document.getElementById("konfirmasi-pesan-nonaktif").textContent = 
            "Apakah Anda yakin ingin menonaktifkan mahasiswa " + nama + "?";
    });
        });

     $(document).ready(function () {
        $(".btn-aktif-mhs").click(function () {
            var nim = $(this).data("nim");
            var nama = $(this).data("nama");
            document.getElementById("nim-input-aktif").value = nim;
            document.getElementById("konfirmasi-pesan-aktif").textContent = 
            "Apakah Anda yakin ingin mengaktifkan mahasiswa " + nama + "?";
        });
    });
</script>
@stop
