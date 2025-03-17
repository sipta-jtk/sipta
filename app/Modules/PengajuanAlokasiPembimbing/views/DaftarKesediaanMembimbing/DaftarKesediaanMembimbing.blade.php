@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
<<<<<<< HEAD
    <h1>Daftar Kesediaan Membimbing</h1>
@stop

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
    <style>
        .truncate {
            white-space: normal;
            overflow: auto;
            max-width: 400px; /* Adjust the max-width as needed */
            max-height: 100px; /* Adjust the max-height as needed */
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#kesediaanTable').DataTable();
        });
    </script>
=======
<h1>Daftar Kesediaan Membimbing</h1>
@stop

@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#kesediaanTable').DataTable();
    });
</script>
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
@stop

@section('content')

<<<<<<< HEAD
    <p>Beranda > <a href="www">Daftar Kesediaan Menjadi Dosen Pembimbing</a></p>
  
    <div class="table-responsive">
    <table id="kesediaanTable" class="table table-bordered">
    <thead>
        <tr>
            <th colspan="7" class="text-center">Dosen Eligible Sebagai Pembimbing 1</th>
            <th colspan="3" class="text-center">Jumlah TA</th>
            <th colspan="2" class="text-center">Kesediaan Membimbing</th>
            <th rowspan="2" class="text-center align-middle">Topik</th>
        </tr>
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">KD DOSEN</th>
            <th class="text-center" style="min-width: 25vh;">NAMA</th>
            <th class="text-center">ID DOSEN</th>
            <th class="text-center" style="min-width: 25vh;">NIP</th>
            <th class="text-center" style="min-width: 10vh;" >KBK</th>
            <th class="text-center" style="min-width: 5vh;">Status Pengumpulan</th>
            <th class="text-center">Jumlah Mhs</th>
            <th class="text-center">Mhs D3</th>
            <th class="text-center">Mhs D4</th>
            <th class="text-center">D3</th>
            <th class="text-center">D4</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td class="text-center">{{ $item->kode_dosen }}</td>
            <td class="text-center">{{ $item->nama }}</td>
            <td class="text-center">{{ $item->id_dosen }}</td>
            <td class="text-center">{{ $item->nip }}</td>
            <td class="text-center">{{ $item->kbk }}</td>
            <td class="text-center">{{ $item->status_pengumpulan }}</td>
            <td class="text-center">{{ $item->Jumlah_Mhs }}</td>
            <td class="text-center">{{ $item->Mhs_D3 }}</td>
            <td class="text-center">{{ $item->Mhs_D4 }}</td>
            <td class="text-center">{{ $item->kesediaan_d3 }}</td>
            <td class="text-center">{{ $item->kesediaan_d4 }}</td>
            <td class="text-left truncate">{{ $item->bidang_tertarik }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
=======
<p>Beranda > <a href="www">Daftar Kesediaan Menjadi Dosen Pembimbing</a></p>

<div class="table-responsive">
    <table id="kesediaanTable" class="table table-bordered">
        <thead>
            <tr class="bg-dark text-white">
                <th colspan="6" class="text-center">Dosen Eligible Sebagai Pembimbing 1</th>
                <th colspan="{{ count($prodiList) }}" class="text-center">Jumlah TA</th>
                <th colspan="{{ count($prodiList) }}" class="text-center">Kesediaan Membimbing</th>
                <th rowspan="2" class="text-center align-middle" style="min-width: 100vh;">Topik</th>
            </tr>
            <tr class="bg-dark text-white">
                <th class="text-center">No</th>
                <th class="text-center">KD DOSEN/ID DOSEN</th>
                <th class="text-center" style="min-width: 45vh;">NAMA</th>
                <th class="text-center" style="min-width: 20vh">NIP</th>
                <th class="text-center" style="min-width: 20vh">KBK</th>
                <th class="text-center" style="min-width: 10vh">Status Pengumpulan</th>
                @foreach ($prodiList as $prodi)
                <th class="text-center">{{ $prodi }}</th>
                @endforeach
                @foreach ($prodiList as $prodi)
                <th class="text-center">Kesediaan {{ $prodi }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $item)
            <tr>
                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                <td class="text-center align-middle">
                    {{ $item['kode_dosen'] }}<br>
                    {{ $item['id_dosen'] }}<br>
                </td>
                <td class="text-center align-middle">{{ $item['nama'] }}</td>
                <td class="text-center align-middle">{{ $item['nip'] }}</td>
                <td class="text-center align-middle">{{ $item['kbk'] }}</td>
                <td class="text-center align-middle">{{ $item['Status_Pengumpulan'] }}</td>

                @foreach ($prodiList as $prodi)
                <td class="text-center align-middle">{{ $item[$prodi] ?? '-' }}</td>
                @endforeach

                @foreach ($prodiList as $prodi)
                <td class="text-center align-middle">{{ $item['Kesediaan_' . $prodi] ?? '-' }}</td>
                @endforeach

                <td class="text-left truncate">{{ $item['bidang'] }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
</div>

@stop