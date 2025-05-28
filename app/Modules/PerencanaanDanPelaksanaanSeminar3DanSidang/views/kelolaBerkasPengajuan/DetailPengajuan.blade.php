@extends('adminlte::page')

@section('title', 'Detail Pengajuan')

@section('content_header')
    <h1>Detail Pengajuan</h1>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Informasi Pengajuan -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Pengajuan</h3>
            </div>
            <div class="card-body">
                <p><strong>Kelompok:</strong> {{ $dataKota->nama_kota }}</p>
                <p><strong>Judul:</strong> {{ $dataKota->judul_ta }}</p>
                <p><strong>Agenda:</strong> {{ $dataKota->jenis_pengajuan }}</p>
                <p><strong>Tanggal Pengajuan:</strong> {{ $dataKota->tanggal_pengajuan }}</p>
            </div>
        </div>

        <!-- Berkas Pengajuan -->
        <div id="container-dokumen" class="container-scroll mt-3"></div>

        <!-- Form Verifikasi -->
        <form action="{{ route('kelola.berkas.verifikasi', ['tipe' => $tipe, 'id' => $dataKota->id_pengajuan]) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="keputusan" id="keputusan" value="">
            <input type="hidden" name="catatan" id="catatan_input" value="">

            <!-- Tombol Tolak & Setuju -->
            <div class="d-flex justify-content-center gap-2 mt-3">
                <button type="button" class="btn btn-danger mx-2" id="btnTolak">Tolak</button>
                <button type="button" class="btn btn-success mx-2" id="btnSetuju">Setuju</button>
            </div>

            <!-- Container Catatan (Tersembunyi Awalnya) -->
            <div class="card mt-3" id="containerCatatan" style="display: none;">
                <div class="card-body">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea class="form-control" id="catatan" rows="3"></textarea>
                    <small id="charCount" class="text-muted">0 / 500 karakter</small>
                </div>
            </div>

            <!-- Tombol Kirim -->
            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary" id="btnKirim" disabled>Kirim</button>
            </div>
        </form>
    </div>
@stop

@section('css')
    <style>
        .container-scroll {
            max-height: 500px;
            overflow-y: auto;
        }
        .file-preview {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .active-btn {
            opacity: 1;
        }
        .disabled-btn {
            opacity: 0.5;
        }
    </style>
@stop

@section('js')
    <script>
        
        $(document).ready(function() {
            let keputusan = ""; // Menyimpan keputusan user

            $('#catatan').on('input', function() {
                const val = $(this).val();
                const max = 500;
                $('#charCount').text(`${val.length} / ${max} karakter`);
                if (val.length > max) {
                    $(this).val(val.substring(0, max));
                    $('#charCount').text(`${max} / ${max} karakter`);
                }
            });


            LihatDokumen();

            // Event ketika tombol "Tolak" diklik
            $('#btnTolak').on('click', function() {
                keputusan = 'tidak_disetujui';
                keputusan2 = 'ditolak';
                $('#keputusan').val(keputusan);
                $('#containerCatatan').slideDown(); // Menampilkan container catatan
                $('#btnSetuju').removeClass('active-btn').addClass('disabled-btn');
                $(this).addClass('active-btn').removeClass('disabled-btn');
                $('#btnKirim').prop('disabled', false);
            });

            // Event ketika tombol "Setuju" diklik
            $('#btnSetuju').on('click', function() {
                keputusan = 'disetujui';
                $('#keputusan').val(keputusan);
                $('#containerCatatan').slideUp(); // Menyembunyikan container catatan
                $('#btnTolak').removeClass('active-btn').addClass('disabled-btn');
                $(this).addClass('active-btn').removeClass('disabled-btn');
                $('#btnKirim').prop('disabled', false);
            });

            // Saat Submit, masukkan catatan ke input hidden
            $('form').on('submit', function() {
                if (keputusan === 'tidak_disetujui') {
                    $('#catatan_input').val($('#catatan').val());
                }
            });
        });

        const prefix = "/{{ env('PREFIX_URL') }}";
        const kategori = '{{ $kategori }}';

        function LihatDokumen() {
            const daftarDokumen = @json($daftarDokumen);
            const container = $('#container-dokumen');
            container.empty();

            // Mapping nama view hanya untuk kategori seminar3 dan sidang
            const displayNameMap = {
                seminar3: [
                    "File Tugas Akhir",
                    "File Presentasi",
                    "FTA 10",
                    "FTA 10a"
                ],
                sidang: [
                    "File Tugas Akhir",
                    "File Presentasi",
                    "FTA 14",
                    "FTA 14a"
                ]
            };

            daftarDokumen.forEach((doc, index) => {
                const filePath = doc.file_path?.trim();
                const fileUrl = filePath ? `${prefix}/storage/${filePath}` : '';
                const fileExtension = filePath?.split('.').pop().toLowerCase();
                const viewerUrl = filePath ? `https://docs.google.com/gview?url=${location.origin}${fileUrl}&embedded=true` : '';
                const downloadUrl = `${prefix}/repository/mahasiswa/${kategori}/${doc.id_dokumen}/download`;

                let previewHTML = '';
                let previewAvailable = true;

                if (filePath && filePath !== '') {
                    if (['pdf', 'png', 'jpg', 'jpeg'].includes(fileExtension)) {
                        previewHTML = `<iframe id="viewDocumentPreview" src="${fileUrl}" width="100%" height="1000px"></iframe>`;
                    } else if (['doc', 'docx', 'ppt', 'pptx'].includes(fileExtension)) {
                        previewHTML = `<iframe id="viewDocumentPreview" src="${viewerUrl}" width="100%" height="1000px"></iframe>`;
                    } else {
                        previewAvailable = false;
                        previewHTML = `<div id="viewPreviewNotAvailable"><p>Preview tidak tersedia untuk file .${fileExtension}</p></div>`;
                    }
                } else {
                    previewAvailable = false;
                    previewHTML = `<div id="viewPreviewNotAvailable"><p>File tidak tersedia.</p></div>`;
                }

                // Gunakan display name jika kategori adalah seminar3 atau sidang
                let displayName = doc.judul;

                if (displayNameMap[kategori]) {
                    displayName = displayNameMap[kategori][index] ?? doc.judul;
                }

                const cardHTML = `
                    <div class="card my-5"> <!-- Tambah my-5 untuk jarak vertikal yang besar -->
                        <div class="card-header">
                            <h3 class="card-title">Berkas Pengajuan</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-center fs-4 fw-bold mb-4"><strong>Nama Dokumen:</strong> ${displayName}</p>
                            ${previewHTML}
                            ${previewAvailable ? `
                                <div class="d-flex gap-2 mt-3">
                                    <a id="view_file_link" href="${fileUrl}" target="_blank" class="btn btn-primary">Lihat File</a>
                                    <a id="view_file_download" href="${downloadUrl}" class="btn btn-success">Download</a>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;

                container.append(cardHTML);
            });
        }

    </script>
@stop
