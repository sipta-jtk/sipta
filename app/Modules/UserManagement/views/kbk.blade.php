@extends('adminlte::page')

@section('title', 'Kelola KBK')
@php
$prefix = env('PREFIX_URL', ''); // Tarik prefix dari env
@endphp
@section('content_header')
    <h1 class="mb-3">Daftar Bidang Keahlian</h1>
    <div>
        @component('UserManagement.components.breadcrumb', [
            'links' => [
                ['url' => url('/' . $prefix . '/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Kelola KBK'],
            ]
        ])
        @endcomponent
    </div>
@stop


@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-end">
            <button class="btn btn-primary btn-md" data-toggle="modal" data-target="#addKBKModal">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>

        <div class="card-body">
            <table id="example" class="table table-striped w-100">
                <thead class="sticky-header bg-dark text-white">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th class="text-left">Kelompok Bidang Keahlian</th>
                        <th class="text-center" style="width: 20%;">Aksi</th> 
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kbkList as $index => $kbk)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-left">{{ $kbk->kbk }}</td>
                            <td class="text-center">
                                  
                                <!-- Tombol Edit KBK -->
                                <a class="btn btn-warning btn-md my-1" title="Ubah" data-toggle="modal" data-target="#editKBKModal{{ $kbk->id_kbk }}">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Tombol Hapus KBK -->
                                <button class="btn btn-danger btn-md my-1" title="Hapus" data-toggle="modal" data-target="#confirmDeleteModal{{ $kbk->id_kbk }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Modal Edit KBK --}}
                        <x-adminlte-modal id="editKBKModal{{ $kbk->id_kbk }}" title="Edit KBK" theme="blue" size="md">
                            <form action="{{ route('kelola-kbk.update', $kbk->id_kbk) }}" method="POST">
                                @csrf
                                @method('PUT') 
                                <div class="row px-3">
                                    <div class="col-12 mb-2">
                                        <label>Nama KBK</label>
                                        <x-adminlte-input name="kbk" value="{{ $kbk->kbk }}" required />
                                    </div>
                                </div>
                                <div class="d-flex pt-3 justify-content-end">
                                    <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal" class="mx-1"/>
                                    <x-adminlte-button theme="success" label="Simpan" type="submit" class="mx-1"/>
                                </div>
                                <x-slot name="footerSlot"></x-slot>
                            </form>
                        </x-adminlte-modal>

                        {{-- Modal Konfirmasi Hapus KBK --}}
                        <x-adminlte-modal id="confirmDeleteModal{{ $kbk->id_kbk }}" title="Konfirmasi Hapus KBK" theme="blue" size="md">
                            <form action="{{ route('kelola-kbk.destroy', $kbk->id_kbk) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="row px-3">
                                    <div class="col-12 mb-2">
                                        <p>Hapus <strong>{{ $kbk->kbk }}</strong>?</p>
                                    </div>
                                </div>
                                <div class="d-flex pt-3 justify-content-end">
                                    <x-adminlte-button theme="primary" label="Batal" data-dismiss="modal" class="mx-1"/>
                                    <x-adminlte-button theme="success" label="Iya" type="submit" class="mx-1"/>
                                </div>
                                <x-slot name="footerSlot"></x-slot>
                            </form>
                        </x-adminlte-modal>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah KBK --}}
    <x-adminlte-modal id="addKBKModal" title="Tambah Kelompok Bidang Keahlian" theme="blue" size="md">
        <form action="{{ route('kelola-kbk.store') }}" method="POST">
            @csrf
            <div class="row px-3">
                <div class="col-12 mb-2">
                    <label>Nama KBK</label>
                    <x-adminlte-input name="kbk" placeholder="Masukkan nama KBK" required />
                </div>
            </div>
            <div class="d-flex pt-3 justify-content-end">
                <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal" class="mx-1"/>
                <x-adminlte-button theme="success" label="Simpan" type="submit" class="mx-1"/>
            </div>
            <x-slot name="footerSlot"></x-slot>
        </form>
    </x-adminlte-modal>
@stop

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#example').DataTable({
            responsive: true,
            ordering: false, 
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari total _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(difilter dari total _MAX_ data)",
                paginate: {
                    first: "<<",
                    last: ">>",
                    next: ">",
                    previous: "<"
                }
            },
            dom: '<"row mb-3"<"col-md-6"l><"col-md-6 text-right"f>>' +
                 '<"row"<"col-12"tr>>' +
                 '<"row mt-3"<"col-md-6"i><"col-md-6 text-right"p>>',
        });

        // Alert Fade Out
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 700);
    });
    </script>
@stop
