@extends('adminlte::page')

@section('title', 'Dokumen Lainnya')

@section('content_header')
<h1 class="mb-3 text-left">Dokumen Lainnya - <span id="kategori-title">{{ $nama_kategori ?? 'Custom' }}</span></h1>
@stop


@section('content')

{{-- Navigasi Tab --}}
<ul class="nav nav-tabs mb-3" id="tabLainnya" role="tablist">
    @foreach($subkategoris as $index => $subkategori)
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $index == 0 ? 'active' : '' }}" 
                id="tab-{{ Str::slug($subkategori->nama_subkategori) }}-tab" 
                data-toggle="tab" 
                data-target="#tab-{{ Str::slug($subkategori->nama_subkategori) }}" 
                type="button" 
                role="tab" 
                aria-controls="tab-{{ Str::slug($subkategori->nama_subkategori) }}" 
                aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                {{ $subkategori->nama_subkategori }}
        </button>
    </li>
    @endforeach
</ul>

<div class="tab-content" id="tabLainnnyaContent">
    @foreach($subkategoris as $index => $subkategori)
    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" 
         id="tab-{{ Str::slug($subkategori->nama_subkategori) }}" 
         role="tabpanel" 
         aria-labelledby="tab-{{ Str::slug($subkategori->nama_subkategori) }}-tab">
        <div class="card">
            <div class="card-header d-flex justify-content-center">
                <h3 class="card-title m-0 text-center text-bold">Dokumen {{ $subkategori->nama_subkategori }}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap px-2 mb-3">
                    <div class="d-flex align-items-center">
                        <!-- Tombol Filter -->
                        <button class="btn btn-primary btn-md mr-2" type="button" data-toggle="collapse" data-target="#filterMenu{{ Str::slug($subkategori->nama_subkategori) }}">
                            <i class="fas fa-filter"></i>
                        </button>

                        <!-- Length Change (Akan diisi otomatis oleh DataTables) -->
                        <div id="custom-length-{{ Str::slug($subkategori->nama_subkategori) }}"></div>
                    </div>

                    <!-- Tombol Tambah -->
                    @if ($status_ta === 'mahasiswa_ta')
                    <button id="btn-tambah-{{ Str::slug($subkategori->nama_subkategori) }}" class="btn btn-success btn-md" 
                        onclick="TambahDokumen('btn-tambah-{{ Str::slug($subkategori->nama_subkategori) }}', '{{ $subkategori->id_subkategori }}')" 
                        data-toggle="modal" data-target="#TambahDokumen">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                    @endif
                </div>

                <!-- Filter Collapse Menu -->
                <div class="collapse mb-3" id="filterMenu{{ Str::slug($subkategori->nama_subkategori) }}">
                    <div class="card card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Versi</label>
                                <select class="form-control version-filter">
                                    <option value="">Semua Versi</option>
                                    @for ($i = 1; $i <= 15; $i++)
                                        <option value="{{ $i }}">V{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Tanggal Dibuat</label>
                                <input type="date" class="form-control date-filter">
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-primary apply-filter-btn">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </div>


                <table class="table table-bordered text-center datatable" data-table="{{ Str::slug($subkategori->nama_subkategori) }}">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="no-sort">No</th>
                            <th>Versi</th>
                            <th>Judul</th>
                            <th>Tanggal Dibuat</th>
                            <th>Terakhir Diedit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $documents = $dokumen->filter(function($doc) use ($subkategori) {
                                return $doc->id_subkategori == $subkategori->id_subkategori;
                            });
                        @endphp
                        
                        @if ($documents->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center">
                                <p class="text-muted">List Dokumen Kosong, Belum Ada Dokumen Yang Ditambahkan</p>
                            </td>
                        </tr>
                        @else
                        @foreach ($documents as $index => $doc)
                        <tr>
                            <td></td>
                            <td>{{ $doc->versi }}</td>
                            <td>{{ $doc->judul }}</td>

                            <td>{{ \Carbon\Carbon::parse($doc->created_at)->translatedFormat('d F Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($doc->updated_at)->translatedFormat('d F Y H:i') }}</td>
                            <td>
                                @if ($status_ta === 'mahasiswa_ta')
                                <!-- Delete button -->
                                <button class="btn btn-sm btn-outline-danger delete-btn"
                                    onclick="HapusDokumen('{{ $doc->id_dokumen }}','{{ $doc->judul }}')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-toggle="modal" data-target="#HapusDokumen">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Edit button -->
                                <button class="btn btn-sm btn-outline-primary edit-btn"
                                    onclick="EditDokumen('{{ $doc->id_dokumen }}','{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}','')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-versi="{{ $doc->versi }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-toggle="modal" data-target="#UbahDokumen">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @endif

                                @if($status_ta === 'mahasiswa_ta' || $isPenguji)
                                <!-- View button -->
                                <button class="btn btn-sm btn-outline-success"
                                    onclick="LihatDokumen('{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}', '{{$doc->kode_fta}}', '{{$doc->notes}}', '{{$doc->username}}')"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-notes="{{ $doc->notes }}"
                                    data-toggle="modal"
                                    data-target="#LihatDokumen">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @endif

                                @if ($isPembimbing)
                                <!-- View button -->
                                <div class="d-flex flex-row justify-content-center">
                                    <button class="btn btn-sm btn-outline-success"
                                        onclick="LihatDokumen('{{ $doc->judul }}','{{ $doc->deskripsi }}','{{ $doc->file_path }}', '{{$doc->kode_fta}}', '{{$doc->notes}}', '{{$doc->username}}')"
                                        data-id="{{ $doc->id_dokumen }}"
                                        data-judul="{{ $doc->judul }}"
                                        data-file="{{ $doc->file_path }}"
                                        data-deskripsi="{{ $doc->deskripsi }}"
                                        data-notes="{{ $doc->notes }}"
                                        data-toggle="modal"
                                        data-target="#LihatDokumen">
                                        <i class="fas fa-edit mr-1"></i> Catatan
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- BAGIAN MODAL -->

<!-- TAMBAH DOKUMEN -->
<x-adminlte-modal id="TambahDokumen" title="Tambah Dokumen" theme="blue" size="lg">
    <form id="addDocumentForm" action="{{ route('Repository.store', $kategori) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_subkategori" id="id_subkategori_hidden" value="">
        <div class="row px-3">
            <div class="col-md-10 mb-2">
                <p class="text-secondary text-md border-bottom">Informasi Dokumen</p>

                <label for="judul">Judul Dokumen</label>
                <x-adminlte-input name="judul" id="judul" required />

                <label for="deskripsi">Deskripsi</label>
                <x-adminlte-textarea name="deskripsi" id="deskripsi" rows="3" />

                <label for="versi">Versi</label>
                <x-adminlte-select name="versi" id="versi">
                    @for ($i = 1; $i <= 15; $i++)
                        <option value="{{ $i }}">V{{ $i }}</option>
                    @endfor
                </x-adminlte-select>

                <label for="file">Upload File</label>
                <div class="input-group mb-3">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="file" name="file" onchange="JudulDokumen()" required>
                        <label class="custom-file-label" for="file">Pilih file</label>
                    </div>
                </div>
            </div>

            <div class="col-md-8 mb-2">
                <p class="text-secondary text-md border-bottom">Dokumen Terunggah</p>

                <label class="form-label">Pratinjau Dokumen</label>
                <div class="document-preview-container" style="height: 400px; border: 1px solid #ddd;">
                    <iframe id="documentPreview" style="width: 100%; height: 100%; border: none;" src=""></iframe>
                    <div id="previewNotAvailable" class="text-center p-5" style="display: none;">
                        <i class="fas fa-file-alt fa-3x mb-3 text-secondary"></i>
                        <p>Preview tidak tersedia untuk jenis file ini</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex pt-3 justify-content-end">
            <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal" class="mx-1" />
            <button type="submit" class="btn btn-success mx-1">Simpan</button>
        </div>
    </form>

    <x-slot name="footerSlot"></x-slot>
</x-adminlte-modal>

<!-- DELETE DOKUMEN -->
<x-adminlte-modal id="HapusDokumen" title="Konfirmasi Hapus Dokumen" theme="danger" size="md" centered>
    <div class="text-center">
        <i class="fas fa-exclamation-circle text-warning mb-3" style="font-size: 3rem;"></i>
        <p class="mb-0">Apakah Anda yakin ingin menghapus dokumen</p>
        <p class="font-weight-bold mb-3" id="delete-document-title"></p>
        <p class="text-muted small">Dokumen yang dihapus tidak dapat dikembalikan lagi</p>
    </div>

    <div class="text-center">
        <form id="deleteDocumentForm" action="" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Hapus</button>
        </form>
    </div>

    <x-slot name="footerSlot"></x-slot>
</x-adminlte-modal>

<!-- Edit/UBAH DOKUMEN -->
<x-adminlte-modal id="UbahDokumen" title="Ubah Dokumen" theme="primary" size="lg">
    <form id="editDocumentForm" action="" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row px-3">
            <div class="col-md-10 mb-2">
                <p class="text-secondary text-md border-bottom">Informasi Dokumen</p>

                <label for="edit_judul">Judul Dokumen</label>
                <x-adminlte-input name="judul" id="edit_judul" required />

                <label for="edit_deskripsi">Deskripsi</label>
                <x-adminlte-textarea name="deskripsi" id="edit_deskripsi" rows="3" />

                <label for="edit_versi">Versi</label>
                <x-adminlte-select name="versi" id="edit_versi">
                    @for ($i = 1; $i <= 15; $i++)
                        <option value="{{ $i }}">V{{ $i }}</option>
                    @endfor
                </x-adminlte-select>

                <label for="edit_file">Ganti File (Opsional)</label>
                <div class="input-group mb-3">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="edit_file" name="file">
                        <label class="custom-file-label" for="edit_file">Pilih file</label>
                    </div>
                </div>
            </div>

            <div class="col-md-10 mb-2">
                <p class="text-secondary text-md border-bottom">File Saat Ini</p>
                <p id="current_filename" class="text-primary"></p>
            </div>
        </div>

        <div class="d-flex pt-3 justify-content-end">
            <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal" class="mx-1" />
            <button type="submit" class="btn btn-primary mx-1">Simpan Perubahan</button>
        </div>
    </form>

    <x-slot name="footerSlot"></x-slot>
</x-adminlte-modal>

<!-- LIHAT DOKUMEN -->
<x-adminlte-modal id="LihatDokumen" title="Lihat Dokumen" theme="success" size="xl">
    <div class="row px-3">
        <div class="col-md-4 mb-2">
            <p class="text-secondary text-md border-bottom">Informasi Dokumen</p>

            <label for="view_judul">Judul</label>
            <x-adminlte-input name="judul" id="view_judul" readonly required />

            <label for="view_deskripsi">Deskripsi</label>
            <x-adminlte-textarea name="deskripsi" id="view_deskripsi" readonly rows="4" />

            <label for="view_username">Pengunggah</label>
            <x-adminlte-input name="username" id="view_username" readonly required />

            <label class="mt-2">Aksi File</label>
            <div class="d-flex flex-column">
                <a href="#" target="_blank" class="btn btn-primary mb-2" id="view_file_link">
                    <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                </a>
                <a href="#" class="btn btn-success" id="view_file_download">
                    <i class="fas fa-download"></i> Unduh
                </a>
            </div>
        </div>

        <div class="col-md-8 mb-2">
            <p class="text-secondary text-md border-bottom">Pratinjau Dokumen</p>
            <div class="document-preview-container" style="height: 470px; border: 1px solid #ddd;">
                <iframe id="viewDocumentPreview" style="width: 100%; height: 100%; border: none;" src=""></iframe>
                <div id="viewPreviewNotAvailable" class="text-center p-5" style="display: none;">
                    <i class="fas fa-file-alt fa-3x mb-3 text-secondary"></i>
                    <p>Preview tidak tersedia untuk jenis file ini</p>
                </div>
            </div>
        </div>

        @if ($status_ta === 'mahasiswa_ta')
        <div class="col-md-12 mb-2">
            <label for="view_notes" class="mt-1">Catatan</label>
            <x-adminlte-textarea name="notes" id="view_notes" readonly rows="4" />
        </div>
        @endif

        @if ($isPembimbing)
        <div class="col-md-12 mb-2">
            <label for="give_notes" class="mt-1">Catatan</label>
            <form id="notesForm" action="" method="POST">
                @csrf
                <x-adminlte-textarea name="notes" id="give_notes" rows="4" />
                <input type="hidden" name="document_id" id="notes_document_id" value="">
                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-primary">Simpan Catatan</button>
                </div>
            </form>
        </div>
        @endif
    </div>

    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal" />
    </x-slot>
</x-adminlte-modal>

@stop

@section('css')
<style>
    .dataTables_length {
        margin-bottom: 10px;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        const dataTables = {};

        // Inisialisasi DataTables untuk setiap tab
        $('.datatable').each(function() {
            const tableType = $(this).data('table');
            
            dataTables[tableType] = $(this).DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "columnDefs": [
                    { "orderable": false, "targets": "no-sort" }
                ],
                "language": {
                    "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "zeroRecords": "Tidak ada data yang cocok",
                    "infoEmpty": "Menampilkan 0 hingga 0 dari 0 entri",
                    "infoFiltered": "(disaring dari _MAX_ total entri)"
                },
                "drawCallback": function() {
                    const api = this.api();
                    
                    // Tambahkan nomor urut
                    api.column(0, { search: 'applied', order: 'applied' })
                        .nodes()
                        .each(function(cell, i) {
                            cell.innerHTML = i + 1;
                        });
                }
            });

            // Pindahkan length dropdown ke container custom
            $(`#custom-length-${tableType}`).html($(`[data-table="${tableType}_wrapper"] .dataTables_length`));
        });

        // Apply filter untuk setiap tabel
        $('.apply-filter-btn').on('click', function() {
            const parentCard = $(this).closest('.card-body');
            const tableType = parentCard.find('.datatable').data('table');
            const versionValue = parentCard.find('.version-filter').val();
            const dateValue = parentCard.find('.date-filter').val();
            
            const table = dataTables[tableType];
            
            // Reset pencarian
            table.search('').columns().search('');
            
            if (versionValue) {
                table.column(1).search('^' + versionValue + '$', true, false);
            }
            
            if (dateValue) {
                table.column(3).search(dateValue);
            }
            
            table.draw();
        });
    });

    const prefix = "/{{ env('PREFIX_URL') }}";
    const kategori = '{{ $kategori }}';

    // File onchange handler
    function JudulDokumen() {
        let fileName = $('#file').val().split('\\').pop(); // Ambil nama file saja
        $('#file').next('.custom-file-label').addClass("selected").html(fileName);
    };

    // Edit dokumen handler
    function EditDokumen(id, judul, deskripsi, filePath) {
        var kategori = '{{ $kategori }}';

        // Set form action update
        $('#editDocumentForm').attr('action', `${prefix}/repository/mahasiswa/${kategori}/${id}`);
        
        // Isi nilai form
        $('#edit_judul').val(judul);
        $('#edit_deskripsi').val(deskripsi);
        $('#current_filename').text(filePath.split('/').pop());
        
        // Reset file input
        $('#edit_file').val('');
        $('#edit_file').next('.custom-file-label').html('Pilih file');
    }

    // Hapus dokumen handler
    function HapusDokumen(id, judul) {
        var kategori = '{{ $kategori }}';
        
        // Set form action delete
        $('#deleteDocumentForm').attr('action', `${prefix}/repository/mahasiswa/${kategori}/${id}`);
        
        // Set judul dokumen dalam konfirmasi
        $('#delete-document-title').text(judul);
    }

    // Tambah dokumen handler
    function TambahDokumen(type, id) {
        var title = '';
        
        // Set hidden input id_subkategori
        $('#id_subkategori_hidden').val(id);
        
        // Reset form
        $('#addDocumentForm')[0].reset();
        $('#file').next('.custom-file-label').html('Pilih file');
    }

    // Lihat dokumen handler
    function LihatDokumen(judul, deskripsi, filePath, kodeFta, notes, username) {
        var kategori = '{{ $kategori }}';
        var id = '';
        
        // Gunakan event data untuk mengambil ID
        id = $('#LihatDokumen').data('doc-id');
        
        // Set nilai untuk form notes
        $('#notes_document_id').val(id);
        $('#notesForm').attr('action', `${prefix}/repository/mahasiswa/save-notes/${id}`);
        
        // Tampilkan informasi dokumen
        $('#view_judul').val(judul);
        $('#view_deskripsi').val(deskripsi);
        $('#view_username').val(username);
        
        if (notes) {
            $('#view_notes').val(notes);
            $('#give_notes').val(notes);
        } else {
            $('#view_notes').val('Belum ada catatan untuk dokumen ini.');
            $('#give_notes').val('');
        }
        
        // Set link download dan preview
        const downloadUrl = `${prefix}/repository/mahasiswa/${kategori}/${id}/download`;
        $('#view_file_download').attr('href', downloadUrl);
        
        // Set preview berdasarkan tipe file
        const fileUrl = filePath;
        $('#view_file_link').attr('href', fileUrl);
        
        const fileExtension = filePath.split('.').pop().toLowerCase();
        const previewableExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
        
        if (previewableExtensions.includes(fileExtension)) {
            $('#viewDocumentPreview').attr('src', fileUrl).show();
            $('#viewPreviewNotAvailable').hide();
        } else {
            $('#viewDocumentPreview').hide();
            $('#viewPreviewNotAvailable').show();
        }
    }

    // File input handler untuk edit form
    $('#edit_file').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Preview dokumen saat memilih file (Tambah Dokumen)
    $('#file').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            const fileExtension = file.name.split('.').pop().toLowerCase();
            const previewableExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
            
            if (previewableExtensions.includes(fileExtension)) {
                reader.onload = function(e) {
                    $('#documentPreview').attr('src', e.target.result).show();
                    $('#previewNotAvailable').hide();
                };
                reader.readAsDataURL(file);
            } else {
                $('#documentPreview').hide();
                $('#previewNotAvailable').show();
            }
        }
    });
</script>
@stop
