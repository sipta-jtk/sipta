@php
$prefix = env('PREFIX_URL','');
@endphp
@extends('adminlte::page')

@section('title', 'Data Mahasiswa')

@section('content_header')
    <h1>Data Mahasiswa</h1>
    <div>
        @component('UserManagement.components.breadcrumb', [
        'links' => [
        ['url' => url('/' . $prefix . '/'), 'label' => 'Beranda'],
        ['url' => '', 'label' => 'Data Mahasiswa']
        ]])
        @endcomponent
        </div>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        
        <table id="datatable" class="table table-striped">
            <thead class="bg-dark text-white">
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Prodi</th>
                    <th>Kelas</th>
                    <th>Tahun Masuk</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                @php
                    @endphp
                @foreach ($mahasiswa as $mhs)
                <tr>
                    <td></td>
                    <td>{{ $mhs->nim }}</td>
                    <td>{{ $mhs->nama }}</td>
                    <td>{{ $mhs->nama_prodi }}</td>
                    <td>{{ $mhs->kelas}}</td>
                    <td>{{ $mhs->tahun_masuk }}</td> 
                    <td><button class="btn btn-primary btn-xs shadow btn-detail-mhs" title="Detail Mahasiswa" data-toggle="modal" data-target="#detailMhs"
                        data-nim="{{$mhs->nim}}"
                        data-nama="{{$mhs->nama}}"
                        data-prodi="{{$mhs->nama_prodi}}"
                        data-kelas="{{$mhs->kelas}}"
                        data-tahun_masuk="{{$mhs->tahun_masuk}}"
                        data-email="{{$mhs->email}}"
                        data-no_whatsapp="{{$mhs->no_whatsapp}}"
                        data-status_user="{{$mhs->status_user}}"
                        >
                        <i class="fas fa-eye"></i></button>
                    </td>
                    @php
                    @endphp
                </tr>
                @endforeach 
            </tbody>
        </table>                       
            </div>
            </div>

                     <x-adminlte-modal id="detailMhs" title="Detail Data Mahasiswa" theme="blue" size='lg' >
                                <p class="text-secondary text-md border-bottom">Identitas Pribadi</p>
                                    <x-adminlte-input name="nama-detail" id="nama-detail" label="Nama"/>
                                    <x-adminlte-input name="nim-detail" id="nim-detail" label="NIM" />
                                    <x-adminlte-input name="Tahun Masuk" label="Tahun Masuk" id="tahun_masuk-detail"/>
                                    <x-adminlte-input name="Kelas" label="Kelas" id="kelas-detail" placeholder="Kelas"/>
                                    <x-adminlte-input name="Prodi" label="Prodi" id="prodi-detail" placeholder="Prodi"/>
                                    <x-adminlte-input name="Status User" id="status_user-detail" placeholder="Prodi"/>
                                <p class="text-secondary text-md border-bottom">Kontak</p>
                                    <x-adminlte-input name="no_whatsapp-detail" id="no_whatsapp-detail" label="Nomor Whatsapp" placeholder="Nomor Whatsapp"  />
                                    <x-adminlte-input name="email-detail" id="email-detail" label="Email" placeholder="Email" />
                     </x-adminlte-modal>

@stop

@section('css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script>
jQuery(document).ready(function($) {
        $('#datatable').DataTable({
            responsive: true,
            columnDefs: [
                { targets: [0, 6], orderable: false } // Kolom No & Aksi tidak bisa disort
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(difilter dari total _MAX_ data)",
                paginate: {
                first: "<<",
                last: ">>",
                next: ">",
                previous: "<"
                }
                },
            drawCallback: function (settings) {
                let api = this.api();
                api.column(0, { page: 'current' }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }
        });
    });


$(document).on('click', '.btn-detail-mhs', function() {
             var nim = $(this).data("nim");
             var nama = $(this).data("nama");
             var email = $(this).data("email");
             var no_whatsapp = $(this).data("no_whatsapp");
             var prodi = $(this).data("prodi");
             var kelas = $(this).data("kelas");
             var tahun_masuk = $(this).data("tahun_masuk");
             var status_user = $(this).data("status_user");

             document.getElementById("nim-detail").value = nim;
             document.getElementById("nama-detail").value = nama;
             document.getElementById("email-detail").value = email;
             document.getElementById("no_whatsapp-detail").value = no_whatsapp;
             document.getElementById("prodi-detail").value = prodi;
             document.getElementById("kelas-detail").value = kelas;
             document.getElementById("tahun_masuk-detail").value = tahun_masuk;
             document.getElementById("status_user-detail").value = status_user;
             console.log(nim);
 
     });
</script>
@stop

