@extends('adminlte::page')

@section('title', 'Daftar Program Studi')

@section('content_header')
    <h1 class="mb-3">Daftar Program Studi</h1>

    <div>
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/'), 'label' => 'Beranda'],
                ['url' => '', 'label' => 'Program Studi']
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
            <button class="btn btn-primary btn-md" data-toggle="modal" data-target="#createModal">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>

        <div class="card-body">
            <table id="example" class="table table-striped w-100">
                <thead class="sticky-header bg-dark text-white">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th>Nama Prodi</th>
                        <th>Ketua Prodi</th>
                        <th class="text-center" style="width: 15%;">Maks Anggota Kota</th>
                        <th class="text-center" style="width: 15%;">Maks Bimbingan</th>
                        <th class="text-center" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programStudi as $index => $prodi)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $prodi->nama_prodi }}</td>
                            <td>{{ $prodi->ketua_prodi ?? '-' }}</td>
                            <td class="text-center">{{ $prodi->maksimal_anggota_kota }}</td>
                            <td class="text-center">{{ $prodi->maksimal_mahasiswa_bimbingan }}</td>
                            <td class="text-center">
                                <a class="btn btn-warning btn-md my-1" title="Ubah" data-toggle="modal" data-target="#editModal{{ $prodi->id_prodi }}">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button class="btn btn-danger btn-md my-1" title="Hapus" data-toggle="modal" data-target="#confirmDeleteModal{{ $prodi->id_prodi }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Modal Edit Program Studi --}}
                        <x-adminlte-modal id="editModal{{ $prodi->id_prodi }}" title="Edit Program Studi" theme="blue" size="md">
                            <form action="{{ route('program-studi.update', $prodi->id_prodi) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row px-3">
                                    <div class="col-12 mb-2">
                                        <label>Nama Prodi</label>
                                        <x-adminlte-input name="nama_prodi" value="{{ $prodi->nama_prodi }}" required />
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label>Ketua Prodi</label>
                                        <x-adminlte-select name="id_kaprodi" required>
                                            <option value="">-- Pilih Dosen --</option>
                                            @foreach($dosen as $d)
                                                <option value="{{ $d->nip }}" {{ ($prodi->kaprodi_nip ?? '') == $d->nip ? 'selected' : '' }}>
                                                    {{ $d->nama_dosen }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label>Maksimal Anggota Kota</label>
                                        <x-adminlte-input type="number" name="maksimal_anggota_kota" value="{{ $prodi->maksimal_anggota_kota }}" min="1" required />
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label>Maksimal Mahasiswa Bimbingan</label>
                                        <x-adminlte-input type="number" name="maksimal_mahasiswa_bimbingan" value="{{ $prodi->maksimal_mahasiswa_bimbingan }}" min="1" required />
                                    </div>
                                </div>

                                <div class="d-flex pt-3 justify-content-end">
                                    <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal" class="mx-1" />
                                    <x-adminlte-button theme="success" label="Simpan" type="submit" class="mx-1" />
                                </div>
                                <x-slot name="footerSlot"></x-slot>
                            </form>
                        </x-adminlte-modal>

                        {{-- Modal Konfirmasi Hapus Program Studi --}}
                        <x-adminlte-modal id="confirmDeleteModal{{ $prodi->id_prodi }}" title="Konfirmasi Hapus" theme="blue" size="md">
                            <form action="{{ route('program-studi.destroy', $prodi->id_prodi) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="row px-3">
                                    <div class="col-12 mb-2">
                                        <p>Hapus <strong>{{ $prodi->nama_prodi }}</strong>?</p>
                                    </div>
                                </div>
                                <div class="d-flex pt-3 justify-content-end">
                                    <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal" class="mx-1" />
                                    <x-adminlte-button theme="success" label="Iya" type="submit" class="mx-1" />
                                </div>
                                <x-slot name="footerSlot"></x-slot>
                            </form>
                        </x-adminlte-modal>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah Program Studi --}}
    <x-adminlte-modal id="createModal" title="Tambah Program Studi" theme="blue" size="md">
        <form action="{{ route('program-studi.store') }}" method="POST">
            @csrf
            <div class="row px-3">
                <div class="col-12 mb-2">
                    <label>Nama Prodi</label>
                    <x-adminlte-input name="nama_prodi" placeholder="Masukkan nama prodi" required />
                </div>
                <div class="col-12 mb-2">
                    <label>Ketua Prodi</label>
                    <x-adminlte-select name="id_kaprodi" required>
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosen as $d)
                            <option value="{{ $d->nip }}">{{ $d->nama_dosen }}</option>
                        @endforeach
                    </x-adminlte-select>
                </div>
                <div class="col-12 mb-2">
                    <label>Maksimal Anggota Kota</label>
                    <x-adminlte-input type="number" name="maksimal_anggota_kota" required min="1" />
                </div>
                <div class="col-12 mb-2">
                    <label>Maksimal Mahasiswa Bimbingan</label>
                    <x-adminlte-input type="number" name="maksimal_mahasiswa_bimbingan" required min="1" />
                </div>
            </div>

            <div class="d-flex pt-3 justify-content-end">
                <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal" class="mx-1" />
                <x-adminlte-button theme="success" label="Simpan" type="submit" class="mx-1" />
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
        var table = $('#example').DataTable({
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

            initComplete: function() {
            $('.dataTables_filter input')
                .attr('placeholder', 'Nama Prodi')
                .css('color', '#6c757d'); 
            }
        });
        

        // Override search untuk hanya cek kolom Nama Prodi (kolom ke-1 dimulai dari 0)
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var input = $('.dataTables_filter input').val().toLowerCase();  // input pencarian
                var namaProdi = data[1].toLowerCase(); // kolom Nama Prodi (index 1)

                if (!input || namaProdi.includes(input)) {
                    return true;
                }
                return false;
            }
        );

        // Refresh table saat ketik search
        $('.dataTables_filter input').on('keyup', function() {
            table.draw();
        });

        // Alert Fade Out
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 700);
    });
    </script>
@stop
