<div class="tab-pane fade" id="tab-srs" role="tabpanel" aria-labelledby="tab-srs-tab">
    <div class="card">
        <div class="card-header d-flex justify-content-center">
            <h3 class="card-title m-0 text-center text-bold">Dokumen SRS Seminar 2</h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap px-2 mb-3">
                <div class="d-flex align-items-center">
                    <!-- Tombol Filter -->
                    <button class="btn btn-primary btn-md mr-2" type="button" data-toggle="collapse" data-target="#filterMenuSrs">
                        <i class="fas fa-filter"></i>
                    </button>

                    <!-- Length Change untuk SRS -->
                    <div id="custom-length-srs"></div>
                </div>

                <!-- Tombol Tambah -->
                <button id="btn-tambah-srs" class="btn btn-primary btn-md" data-toggle="modal" data-target="#TambahDokumen" data-id-subkategori="{{ $subkategoriSrs->id_subkategori }}">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>

            <!-- Filter -->
            <div class="collapse filter-menu" id="filterMenuSrs" data-target="srs">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-filter mr-2"></i>Filter Data
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Versi</label>
                                <select class="form-control version-filter">
                                    <option value="">Semua</option>
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
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-primary apply-filter-btn">
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabel Data -->
            <table class="table table-bordered text-center datatable" data-table="srs">
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
                    @if ($srs->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">
                            <p class="text-muted">List Dokumen Kosong, Belum Ada Dokumen Yang Ditambahkan</p>
                        </td>
                    </tr>
                    @else
                    @foreach ($srs as $index => $doc)
                    <tr>
                        <td></td>
                        <td>{{ $doc->versi }}</td>
                        <td>{{ $doc->judul }}</td>
                        <td>{{ \Carbon\Carbon::parse($doc->created_at)->translatedFormat('H:i d F Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($doc->updated_at)->translatedFormat('H:i d F Y') }}</td>
                        <td>
                            @if ($status_ta === 'mahasiswa_ta')
                                <button class="btn btn-sm btn-outline-primary edit-btn"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-versi="{{ $doc->versi }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-toggle="modal" data-target="#UbahDokumen">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-danger delete-btn"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-toggle="modal" data-target="#HapusDokumen">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-success view-btn"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-toggle="modal"
                                    data-target="#LihatDokumen">
                                    <i class="fas fa-eye"></i>
                                </button>
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
