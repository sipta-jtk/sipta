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
                <p><strong>Kelompok:</strong> {{ $dataKota->id_kota }}</p>
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

            LihatDokumen();

            // Event ketika tombol "Tolak" diklik
            $('#btnTolak').on('click', function() {
                keputusan = 'tidak_disetujui';
                keputusan2 = 'ditolak';
                $('#keputusan').val(ditolak);
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

            daftarDokumen.forEach(doc => {
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

                const cardHTML = `
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Berkas Pengajuan</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Nama Dokumen:</strong> ${doc.judul}</p>
                            ${previewHTML}
                            ${previewAvailable ? `
                                <a id="view_file_link" href="${fileUrl}" target="_blank" class="btn btn-primary mt-2">Lihat File</a>
                                <a id="view_file_download" href="${downloadUrl}" class="btn btn-success mt-2">Download</a>
                            ` : ''}
                        </div>
                    </div>
                `;

                container.append(cardHTML);
            });
        }

    </script>
@stop
