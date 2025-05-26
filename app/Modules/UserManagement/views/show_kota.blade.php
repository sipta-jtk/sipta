@extends('adminlte::page')

@section('title', 'Data KoTA')

@section('content_header')
    <h1>Data Kota</h1>
    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
        'links' => [
        ['url' => url('/sipta-dev/'), 'label' => 'Beranda'],
        ['url' => url(), 'label' => 'Data Kelompok KoTA']
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
                    <th>Kota</th>
                    <th>Tahun Kota</th>
                    <th>Judul TA</th>
                    <th>Bidang</th>
                    <th>Status KoTA</th>

                </tr>
            </thead>
            <tbody>
                @php
                        $no = 1;
                    @endphp
                @foreach ($kota as $kt)
                <tr>
                    <td>{{$no}}</td>
                    <td>{{ $kt->nama_kota }}</td>
                    <td>{{$kt->tahun_kota}}</td>
                    <td>{{$kt->judul_ta}}</td>
                    <td>{{$kt->bidang}}</td>
                    <td>{{$kt->status_kota}}</td>
                    {{-- <td>{{ $mhs->nama }}</td>
                    <td>{{ $mhs->nama_prodi }}</td>
                    <td>{{ $mhs->kelas}}</td>
                    <td>{{ $mhs->tahun_masuk }}</td>  --}}
                    {{-- <td><button class="btn btn-primary btn-xs shadow btn-detail-mhs" title="Detail Mahasiswa" data-toggle="modal" data-target="#detailMhs"
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
                    </td> --}}
                    @php
                    $no++; 
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
<script>
    //  $(document).ready(function () {
    //      $(".btn-detail-mhs").click(function () {
    //          var nim = $(this).data("nim");
    //          var nama = $(this).data("nama");
    //          var email = $(this).data("email");
    //          var no_whatsapp = $(this).data("no_whatsapp");
    //          var prodi = $(this).data("prodi");
    //          var kelas = $(this).data("kelas");
    //          var tahun_masuk = $(this).data("tahun_masuk");
    //          var status_user = $(this).data("status_user");

    //          document.getElementById("nim-detail").value = nim;
    //          document.getElementById("nama-detail").value = nama;
    //          document.getElementById("email-detail").value = email;
    //          document.getElementById("no_whatsapp-detail").value = no_whatsapp;
    //          document.getElementById("prodi-detail").value = prodi;
    //          document.getElementById("kelas-detail").value = kelas;
    //          document.getElementById("tahun_masuk-detail").value = tahun_masuk;
    //          document.getElementById("status_user-detail").value = status_user;
    //          console.log(nim);
 
    //      });
    //  });
</script>
@stop

