@extends('adminlte::page')

@section('title', 'Data Dosen')

@section('content_header')
    <h1>Data Dosen</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
        'links' => [
        ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
        ['url' => url(), 'label' => 'Manajemen Akun Dosen']
        ]])
        @endcomponent
        </div>
@stop

@section('content')
<!-- Button Tambah Dosen -->

@if(session('successrole'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<div class="card">
    {{-- <div class="card-header d-flex justify-content-between align-items-left">
    </div> --}}
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                <button class="btn btn-primary mb-3 updateRoleBulk">
                    <i class="fa fa-check"></i> Tampilkan Centang
                </button>
              </div>
        <div style="gap: 0.5rem;">
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#uploadExcel">
                <i class="fa fa-upload"></i> Unggah Excel
            </button>
            
            <a href="{{ route('download.template-dosen') }}" class="btn btn-primary mb-3">
                <i class="fa fa-download"></i> Unduh Format Excel
            </a>
            
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addNewDosen">
                <i class="fa fa-plus"></i> Tambah Dosen
            </button>
        </div>
              
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
                    <th class="first_column"><input type="checkbox" id="select_all"></th>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Peran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($dosen as $d)
                <tr>
                    <td class="first_column">
                    @if ($d->role_dosen != 'kajur')
                        <input type="checkbox" class="user_checkbox" value="{{ $d->nip }}">
                    @endif
                    
                    </td>
                    <td></td>
                    <td>{{ $d->nip }}</td>
                    <td>{{ $d->nama }}</td>
                    <td>{{ $d->email }}</td>
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
                        @if($d->status_user != 'nonaktif')
                        <button class="btn btn-danger btn-xs shadow btn-nonaktif-dosen" title="Nonaktifkan Dosen" data-toggle="modal" data-target="#nonAktifDosen" 
                        data-nip="{{ $d->nip }}"
                        data-nama="{{ $d->nama }}">
                        <i class="fas fa-power-off m-1"></i>
                    </button>
                    @else
                    <button class="btn btn-success btn-xs shadow btn-aktif-dosen" title="Aktifkan Dosen" data-toggle="modal" data-target="#AktifDosen" 
                        data-nip="{{ $d->nip }}"
                        data-nama="{{ $d->nama }}">
                        <i class="fas fa-power-off m-1"></i> 
                    </button>
                    @endif
    

                        {{-- Edit Button --}}
                        <button class="btn btn-warning btn-xs me-2 shadow btn-update-dosen" title="Edit Data" data-toggle="modal" data-target="#updateDosen" class="bg-purple"
                            data-nip="{{ $d->nip }}"
                            data-nama="{{ $d->nama }}"
                            data-role="{{ $d->role_dosen }}"
                            data-id_dosen="{{ $d->id_dosen }}"
                            data-email="{{ $d->email }}"
                            data-no_whatsapp="{{ $d->no_whatsapp }}"
                            data-kode_dosen="{{ $d->kode_dosen }}"
                            data-id_kbk="{{ $d->id_kbk }}">

                              <i class="fas fa-edit m-1"></i>
                            
                        </button>
                        @if($d->role_dosen != 'kajur')
                            <button class="btn btn-xs btn-primary shadow btn-change-role" title="Ubah Peran" data-toggle="modal" data-target="#changeRole" class="bg-purple"
                            data-nip="{{ $d->nip }}"
                            data-nama="{{ $d->nama }}"
                            data-role="{{ $d->role_dosen }}">
                            <i class="fas fa-user-cog m-1"></i> 
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
                        <x-adminlte-modal id="updateDosen" title="Mengubah Data Dosen" theme="blue" size='lg' >
                            <form action="{{route('dosen.updateDosen')}}" method="POST">
                                @csrf
                                <p class="text-secondary text-md border-bottom">Identitas Pribadi</p>

                                    <x-adminlte-input name="nama" pattern="[A-Za-z\s.,]+" id="nama-update" label="Nama" placeholder="Nama Lengkap"
                                        fgroup-class="col-md" disable-feedback required/>

                                        <x-adminlte-input name="nip" id="nip-update" label="NIP" placeholder="NIP"
                                            fgroup-class="col-md" disable-feedback  readonly   />

                                    
                                    <x-adminlte-input name="id" id="id_dosen-update" label="ID Dosen" placeholder="ID"
                                        fgroup-class="col-md" disable-feedback  required  />

                                        <x-adminlte-input name="kode" label="Kode Dosen" id="kode_dosen-update" placeholder="Kode"
                                        fgroup-class="col-md" disable-feedback  required  />
                                    <x-adminlte-select2 name="id_kbk" label="KBK" id="kbk-update" fgroup-class="col-md" required>
                                        <option selected disabled>Pilih KBK ....</option>
                                        @foreach($listkbk as $kbk)
                                        <option value="{{ $kbk->id_kbk }}">{{ $kbk->kbk }}</option>
                                    @endforeach
                                </x-adminlte-select2>

                                <p class="text-secondary text-md border-bottom">Kontak</p>
                                    <x-adminlte-input name="no_wa" id="no_whatsapp-update" label="Nomor Whatsapp" placeholder="Nomor Whatsapp"
                                               fgroup-class="col-md" disable-feedback  required  />

                                    <x-adminlte-input name="email" id="email-update" label="Email" placeholder="Email"
                                            fgroup-class="col-md" disable-feedback  readonly />

                                <div class="d-flex justify-content-end">
                                    <x-adminlte-button theme="danger" label="Tutup"
                                    data-dismiss="modal" class="mx-1"/>
                                    <x-adminlte-button theme="success" label="Simpan"
                                     class="mx-1" type="submit"/>
                                    </div>
                                    <x-slot name="footerSlot"></x-slot>
                            </form>
                        </x-adminlte-modal>
                        <x-adminlte-modal id="changeRole" title="Manajemen Peran Dosen" theme="blue" size='lg'>
                            
                            <form action="{{route('dosen.update_role')}}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="nip" class="form-label">NIP</label>
                                    <input type="text" name="nip" class="form-control" id="nip-input" placeholder="NIP" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" name="nama" pattern="[A-Za-z\s.,]+" class="form-control" id="nama-input" placeholder="Nama" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="role" class="form-label">Role Saat Ini</label>
                                    <select class="form-control" id="role-input" name="role">
                                        <option value="dosen">Dosen</option>
                                        <option value="koordinator_ta">Koordinator TA</option>
                                        <option value="kajur">Ketua Jurusan</option>
                                    </select>
                                </div>
                                <div class="d-flex pt-3 justify-content-end">
                                    <x-adminlte-button theme="danger" label="Tutup"
                                    data-dismiss="modal" class="mx-1"/>
                                    <x-adminlte-button theme="success" label="Simpan"
                                     class="mx-1" type="submit"/>
                                    </div>
                                    <x-slot name="footerSlot"></x-slot>
                            </form>
                         </x-adminlte-modal>

                        <x-adminlte-modal id="addNewDosen" title="Menambah Dosen Baru" theme="blue" size='lg' >
                            <form action="{{route('dosen.add_new_dosen')}}" method="POST">
                                @csrf
                                <p class="text-secondary text-md border-bottom">Identitas Pribadi</p>
                                    <x-adminlte-input name="nama" pattern="[A-Za-z\s.,]+" label="Nama" placeholder="Nama Lengkap"
                                    fgroup-class="col-md" disable-feedback required/>

                                        <x-adminlte-input name="nip" label="NIP" placeholder="NIP"
                                            fgroup-class="col-md" disable-feedback  required  />

                                    <x-adminlte-input name="id" label="ID Dosen" placeholder="Misal: AE (sebagai Ardhian Ekawijana)"
                                        fgroup-class="col-md" disable-feedback  required  />
                                        <x-adminlte-input name="kode" label="Kode Dosen" placeholder="Misal: KO001N"
                                        fgroup-class="col-md" disable-feedback  required  />
                                    <x-adminlte-select2 name="id_kbk" label="KBK" fgroup-class="col-md" required>
                                        <option selected disabled>Pilih KBK ....</option>
                                        @foreach($listkbk as $kbk)
                                        <option value="{{ $kbk->id_kbk }}">{{ $kbk->kbk }}</option>
                                    @endforeach
                                </x-adminlte-select2>

                                <x-adminlte-select2 name="status_dosen" label="Status" fgroup-class="col-md" required>
                                    <option selected disabled>Pilih Status ....</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Non Aktif</option>

                            </x-adminlte-select2>
                            <p class="text-secondary text-md border-bottom">Kontak</p>

                                <x-adminlte-input name="no_wa" label="Nomor Whatsapp" placeholder="Nomor Whatsapp"
                                            fgroup-class="col-md" disable-feedback  required  />
                                <x-adminlte-input name="email" label="Email" placeholder="Email"
                                            fgroup-class="col-md" disable-feedback  required  />

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
                                <form action="{{route('import-dosen')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <x-adminlte-input-file name="file" label="Upload Excel" placeholder="Pilih file excel ..." fgroup-class="col-md" disable-feedback required accept=".xls,.xlsx,.csv"/>
                                    <div class="d-flex justify-content-end">
                                    <x-adminlte-button theme="danger" label="Tutup"
                                    data-dismiss="modal" class="mx-1"/>
                                    <x-adminlte-button theme="success" label="Pratinjau"
                                     class="mx-1" type="submit"/>
                                    </div>
                                    <x-slot name="footerSlot"></x-slot>
                                </form>
                        </x-adminlte-modal>

                        <x-adminlte-modal id="nonAktifDosen" title="Menonaktifkan Dosen" theme="blue" size='lg'>
                            <form action="{{route('nonaktif-dosen')}}" method="POST">
                                @csrf
                                <h4 id="konfirmasi-pesan-nonaktif">Yakin ingin menonaktifkan Dosen ?</h4>
                                <input type="hidden" name="nip" class="form-control" id="nip-input-nonaktif">
                                <div class="d-flex justify-content-end">
                                    <x-adminlte-button theme="success" label="Nonaktifkan"
                                     class="mx-1" type="submit"/>
                                    </div>
                                    <x-slot name="footerSlot"></x-slot>
                            </form>
                    </x-adminlte-modal>
                    <x-adminlte-modal id="aktifDosen" title="Aktifkan Dosen" theme="blue" size='lg'>
                            <form action="{{route('aktif-dosen')}}" method="POST" >
                                @csrf
                                <h4 id="konfirmasi-pesan-aktif">Yakin ingin mengaktifkan Dosen ?</h4>
                                <input type="hidden" name="nip" class="form-control" id="nip-input-aktif">
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
<style>
    .first_column {
        display: none;
    }
    
</style>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script>
jQuery(document).ready(function($) {

    $('#datatable').DataTable({
            responsive: true,
            columnDefs: [
                { targets: [1, 6], orderable: false } // Kolom No & Aksi tidak bisa disort
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "→",
                    previous: "←"
                },
                zeroRecords: "Data tidak ditemukan",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                infoFiltered: "(disaring dari _MAX_ total entri)"
            },
            drawCallback: function (settings) {
                let api = this.api();
                api.column(1, { page: 'current' }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }
        });
});

    document.addEventListener('DOMContentLoaded', function() {
        const button = document.querySelector('.updateRoleBulk');
        const columns = document.querySelectorAll('.first_column');

        button.addEventListener('click', function() {

            if (columns[0].style.display === 'none' || columns[0].style.display === '') {
                document.querySelectorAll('.first_column').forEach(el => {
                el.style.display = 'table-cell'; // Tampilkan kolom
                button.classList.remove('btn-primary');
                button.classList.add('btn-danger');
                button.innerHTML = '<i class="fa fa-times"></i> Sembunyikan Centang';
                $('#role_section').show();
            });
            } else {
                document.querySelectorAll('.first_column').forEach(el => {
                el.style.display = 'none'; 
                button.classList.remove('btn-danger');
                button.classList.add('btn-primary');
                button.innerHTML = '<i class="fa fa-check"></i> Tampilkan Centang';
                $('#role_section').hide();
                
            });
            }

            columns.forEach(el => {
                el.style.display = isHidden ? 'table-cell' : 'none';
            });

            
    });
});
    $(document).ready(function () {

    $('#select_all').on('change', function () {
        $('.user_checkbox').prop('checked', this.checked);
    });

    // Event klik tombol update
    $('#updateRoleButton').on('click', function () {
        var selectedUsers = $('.user_checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        var newRole = $('#new_role').val();

        if (selectedUsers.length === 0 || newRole === "") {
            alert("Pilih satu / lebih user dan role baru!");
            return;
        }

        $.ajax({
            url: "{{ route('updateBulkRole') }}",
            type: "POST",
            data: {
                nip: selectedUsers,
                role_dosen: newRole,
                _token: "{{ csrf_token() }}"
            },
            success: function () {
            Swal.fire({
                title: "Berhasil!",
                text: "Peran dosen berhasil diperbarui!",
                icon: "success",
                confirmButtonText: "OK"
            }).then(() => {
                location.reload();
            });
},
            error: function () {
                alert("Terjadi kesalahan, coba lagi.");
            }
        });
    });
});


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
    if (!fileInput) return;

    const label = fileInput.closest('.input-group')?.querySelector('.custom-file-label');
    if (!label) return;

    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            label.textContent = this.files[0].name;
        } else {
            label.textContent = "Choose a file...";
        }
    });
});
 
    

// Event delegation untuk semua tombol
$(document).on('click', '.btn-change-role', function() {
    var data = $(this).data();
    $("#nip-input").val(data.nip);
    $("#nama-input").val(data.nama);
    $("#role-input").val(data.role);
});

$(document).on('click', '.btn-update-dosen', function() {
    var data = $(this).data();
    $("#nip-update").val(data.nip);
    $("#nama-update").val(data.nama);
    $("#email-update").val(data.email);
    $("#no_whatsapp-update").val(data.no_whatsapp);
    $("#id_dosen-update").val(data.id_dosen);
    $("#kode_dosen-update").val(data.kode_dosen);
    $("#kbk-update").val(data.id_kbk).trigger('change');
});

$(document).on('click', '.btn-nonaktif-dosen', function() {
    var data = $(this).data();
    $("#nip-input-nonaktif").val(data.nip);
    $("#konfirmasi-pesan-nonaktif").text(
        `Apakah Anda yakin ingin menonaktifkan dosen ${data.nama}?`
    );
});

$(document).on('click', '.btn-aktif-dosen', function() {
    var data = $(this).data();
    $("#nip-input-aktif").val(data.nip);
    $("#konfirmasi-pesan-aktif").text(
        `Apakah Anda yakin ingin mengaktifkan dosen ${data.nama}?`
    );
});
    

 
</script>