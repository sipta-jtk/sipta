<div class="tab-pane fade" id="tab-fta" role="tabpanel" aria-labelledby="tab-fta-tab">
    <div class="card">
        <div class="card-header d-flex justify-content-center">
            <h3 class="card-title m-0 text-center text-bold">Formulir Tugas Akhir (FTA) Seminar 2</h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap px-2 mb-3">
                <div class="d-flex align-items-center">
                    <!-- Tombol Filter -->
                    <button class="btn btn-primary btn-md mr-2" type="button" data-toggle="collapse" data-target="#filterMenuFta">
                        <i class="fas fa-filter"></i>
                    </button>

                    <!-- Length Change untuk FTA -->
                    <div id="custom-length-fta"></div>
                </div>

                <!-- Tombol Tambah -->
                <button id="btn-tambah-fta" class="btn btn-primary btn-md" data-toggle="modal" data-target="#TambahDokumen" data-id-subkategori="{{ $subkategoriFta->id_subkategori }}">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>

            <!-- Filter -->
            <div class="collapse filter-menu" id="filterMenuFta" data-target="fta">
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

                            <div class="col-md-4">
                                <div class="form-group text-secondary">
                                    <label>
                                        <i class="fas fa-project-diagram mr-1"></i> Kode FTA
                                    </label>
                                    <select class="form-control select2bs4 kode-fta-filter" style="width: 100%;">
                                        <option selected disabled>Pilih Kode FTA</option>
                                        <option value="FTA-06">FTA-06 (Bukti Bimbingan Seminar II)</option>
                                        <option value="FTA-06a">FTA-06a (Resume Bimbingan)</option>
                                        <option value="FTA-07">FTA-07 (Penilaian Seminar II)</option>
                                        <option value="FTA-08">FTA-08 (Masukan Seminar II)</option>
                                        <option value="FTA-09">FTA-09 (Kehadiran Seminar II)</option>
                                        <option value="FTA-09a">FTA-09a (Lesson Learnt Seminar II)</option>
                                        <option value="FTA-023">FTA-023 (Persetujuan Pelaksanaan Seminar II/III Tugas Akhir)</option>
                                    </select>
                                </div>
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
            <table class="table table-bordered text-center datatable" data-table="fta">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="no-sort">No</th>
                        <th>Kode-FTA</th>
                        <th>Versi</th>
                        <th>Judul</th>
                        <th>Tanggal Dibuat</th>
                        <th>Terakhir Diedit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($fta->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">
                            <p class="text-muted">List Dokumen Kosong, Belum Ada Dokumen Yang Ditambahkan</p>
                        </td>
                    </tr>
                    @else
                    @foreach ($fta as $index => $doc)
                    <tr>
                        <td></td> {{-- No otomatis diisi dari JS --}}
                        <td>{{ $doc->kode_fta }}</td>
                        <td>{{ $doc->versi }}</td>
                        <td>{{ $doc->judul }}</td>
                        <td>{{ \Carbon\Carbon::parse($doc->created_at)->translatedFormat('H:i d F Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($doc->updated_at)->translatedFormat('H:i d F Y') }}</td>
                        <td>
                            @if ($status_ta === 'mahasiswa_ta')
                                <!-- Tombol Aksi -->
                                <button class="btn btn-sm btn-outline-primary edit-btn"
                                    data-id="{{ $doc->id_dokumen }}"
                                    data-judul="{{ $doc->judul }}"
                                    data-kode_fta="{{ $doc->kode_fta }}"
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
                                    data-kode_fta="{{ $doc->kode_fta }}"
                                    data-file="{{ $doc->file_path }}"
                                    data-deskripsi="{{ $doc->deskripsi }}"
                                    data-toggle="modal" data-target="#LihatDokumen">
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
