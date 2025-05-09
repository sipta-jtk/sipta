@extends('adminlte::page')

@php
    $title = match ($data['namaFta']) {
        'seminar i' => 'MASUKAN SEMINAR I',
        'seminar ii' => 'MASUKAN SEMINAR II',
        'seminar iii' => 'MASUKAN SEMINAR III',
        default => 'SIDANG AKHIR',
    };
@endphp

@section('title', $title)

@section('content_header')
    <div class="container-fluid p-3">
        <!-- Breadcrumb -->
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => route('beranda.get'), 'label' => 'Beranda'],
                ['url' => route('pengisian.nilai', ['namaFta' => $data['namaFta'], 'idKota' => $data['id_kota'], 'idProdi' => $seminar['id_prodi']]), 
                    'label' => match ($data['namaFta']) {
                        'seminar i' => '',
                        'seminar ii' => 'Penilaian Seminar II',
                        'seminar iii' => 'Penilaian Seminar III',
                        default => 'Penilaian Sidang Akhir'
                    }
                ],
                ['url' => '', 'label' => match ($data['namaFta']) {
                        'seminar i' => 'Masukan Seminar I',
                        'seminar ii' => 'Masukan Seminar II',
                        'seminar iii' => 'Masukan Seminar III',
                        default => 'Masukan Sidang Akhir'
                    }
                ],
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">MASUKAN {{ strtoupper($data['namaFta']) }}</h1>
    </div>
@stop

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="card p-4">
        <div class="row">
            <!-- Kode FTA -->
            <div class="col-md-12">
                <strong>Kode FTA</strong> <br>
                <span>{{ $data['kode_fta'] }}</span>
            </div>

            <!-- Tanggal, Waktu, ID KoTA -->
            <div class="col-md-2 mt-3">
                <strong>Pada Hari/Tanggal</strong> <br>
                <span>{{ $jadwal ? \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <div class="col-md-2 mt-3">
                <strong>Waktu</strong> <br>
                <span>
                    @if($jadwal)
                        {{ \Carbon\Carbon::parse($jadwal->start)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end)->format('H:i') }}
                    @else
                        -
                    @endif
                </span>
            </div>
            <div class="col-md-2 mt-3">
                <strong>KoTA</strong> <br>
               <span> {{ $keteranganUmumPenilaian->nama_kota }}</span>
            </div>
        </div>

        <!-- Data Mahasiswa dalam Tabel -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($keteranganUmumPenilaian->mahasiswa as $key => $mhs)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $mhs->nim }}</td>
                                    <td>{{ $mhs->user->nama }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Topik Tugas Akhir -->
        <div class="row mt-4">
            <div class="col-md-12">
                <strong>
                    {{ match ($data['namaFta']) {
                        'seminar i' => 'Usulan Topik Tugas Akhir',
                        default => 'Topik Tugas Akhir'
                    } }}
                </strong> <br>
                <span>{{ $keteranganUmumPenilaian->judul_ta }}</span>
            </div>
        </div>

        <!-- Tombol Preview Dokumen -->
        <div class="row mt-4">
            <div class="col-md-12">
                <strong>Dokumen
                    {{ match ($data['namaFta']) {
                        'seminar i' => 'Seminar I',
                        'seminar ii' => 'Seminar II',
                        'seminar iii' => 'Seminar III',
                        'sidang akhir' => 'Sidang Akhir',
                        default => ''
                    } }}
                </strong> <br>
                <button type="button" class="btn btn-primary btn-prev" data-toggle="modal" data-target="#previewModal" onclick="loadPreview('dokumen/a4xQcjsyixgnm6AOzkYOCnfVY7xgUB0j3LwgZ8Uf.pdf')">
                    Laporan
                </button>
                <button type="button" class="btn btn-primary btn-prev" data-toggle="modal" data-target="#previewModal" onclick="loadPreview('https://drive.google.com/file/d/1csAcC_MeS9YI3BkdW-i747-aG92-8yLf/view?usp=sharing')">
                    Power Point
                </button>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="previewModalLabel">Preview</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="previewFrame" src="" width="100%" height="500px" frameborder="0"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Judul Form -->      
        <h3 class="heading-spacing text-center">
            {{ match ($data['namaFta']) {
                'seminar i' => 'EVALUASI',
                'seminar ii', 'seminar iii' => 'ISI MASUKAN',
                'sidang akhir' => 'CATATAN PERBAIKAN LAPORAN',
                default => ''
            } }}
        </h3>

        <!-- Form Penilaian -->
        @php
            // Cek apakah data masukan sudah ada
            $isEdit = isset($seminar) && $seminar->kategoriPenilaian->isNotEmpty();
            $actionUrl = $isEdit
                ? route('pengisian.masukan.edit', ['namaFta' => Str::slug($data['namaFta']), 'idKota' => $data['id_kota']])
                : route('pengisian.masukan.store', ['namaFta' => Str::slug($data['namaFta']), 'idKota' => $data['id_kota']]);
        @endphp
        
        <form action="{{ $actionUrl }}" method="POST">
            @csrf
            @if ($isEdit)
                @method('PATCH')
            @else
                @method('POST')
            @endif

            @if($aspekFeedback->isEmpty())
                <div class="alert alert-warning">Belum ada aspek feedback untuk {{ strtoupper($data['namaFta']) }}.</div>
            @else
            @foreach ($aspekFeedback as $index => $feedback)
                <div class="form-group mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="{{ Str::slug($feedback->nama_aspek_feedback) }}" class="mb-1">
                            Masukan untuk {{ $feedback->nama_aspek_feedback }}
                        </label>
                        <div class="text-muted char-counter" id="char-count-{{ $index }}">0/100 karakter</div>
                    </div>

                    <input type="hidden" name="feedback[{{ $index }}][id_fta]" value="{{ $data['kode_fta'] }}">
                    <input type="hidden" name="feedback[{{ $index }}][nama_aspek_feedback]" value="{{ $feedback->nama_aspek_feedback }}">
                    <input type="hidden" name="feedback[{{ $index }}][id_feedback]" value="{{ $feedback->id_feedback }}">

                    @php
                        $existingFeedback = $data['detailFeedback']->get($feedback->id_feedback);
                        $oldValue = old("feedback.{$index}.masukan", $existingFeedback->isi_feedback ?? '');
                    @endphp

                    <input id="feedback-{{ $index }}" type="hidden" name="feedback[{{ $index }}][masukan]" value="{{ $oldValue }}">
                    <trix-editor input="feedback-{{ $index }}"></trix-editor>
                    <span>Min. 15 karakter</span>
                </div>
            @endforeach
            @endif
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-md my-1 {{ $aspekFeedback->isEmpty() ? 'disabled' : '' }}">
                    Simpan <i class="mx-1 my-1 fa-solid fa-floppy-disk me-1"></i>
                </button>
            </div>
        </form>
    </div>
@stop

@section('css')
    <!-- Bootstrap dan Library lainnya -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/pemberian_nilai_dan_feedback.css') }}">

    <style>
        .text-danger {
            color: red !important;
            font-weight: bold;
        }

        .char-counter {
            transition: color 0.3s ease;
        }
    </style>
@stop

@section('js')
    <!-- Trix Editor Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/pemberian_nilai_dan_feedback.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const editors = document.querySelectorAll('trix-editor');

            editors.forEach((editor, index) => {
                const counter = document.querySelector(`#char-count-${index}`);
                const hiddenInput = document.querySelector(`#feedback-${index}`);

                // Update hidden input dengan HTML + hitung karakter
                const updateContent = () => {
                    // 1. Simpan isi HTML ke hidden input
                    const inputId = editor.getAttribute("input");
                    const inputWithHtml = document.getElementById(inputId);
                    if (inputWithHtml) {
                        inputWithHtml.value = editor.innerHTML;
                    }

                    // 2. Hitung karakter dari plain text
                    const plainText = editor.editor.getDocument().toString().trim();
                    const charLength = plainText.length;

                    // 3. Update counter tampilan
                    counter.textContent = `${charLength}/100 karakter`;

                    // 4. Tambah/hapus class untuk warna
                    if (charLength < 15 || charLength > 100) {
                        counter.classList.add("text-danger");
                        counter.classList.remove("text-muted");
                    } else {
                        counter.classList.remove("text-danger");
                        counter.classList.add("text-muted");
                    }
                };

                // Event listener untuk setiap perubahan
                editor.addEventListener("trix-change", updateContent);

                // Inisialisasi awal
                updateContent();
            });
        });

        function loadPreview(url) {
            let fileId = extractDriveFileId(url);
            if (fileId) {
                let embedUrl = `https://drive.google.com/file/d/${fileId}/preview`;
                document.getElementById('previewFrame').src = embedUrl;
            } else {
                alert("Format link tidak valid!");
            }
        }

        function extractDriveFileId(url) {
            let match = url.match(/[-\w]{25,}/);
            return match ? match[0] : null;
        }

        // $('.view-btn').on('click', function() {
        function LihatDokumen(judul, deskripsi, filePath, kodeFta) {
            // console.log("Tes");
            // var judul = $(this).data('judul');
            // var deskripsi = $(this).data('deskripsi');
            // var filePath = $(this).data('file');
            // var kodeFta = $(this).data('kode-fta');

            $('#view_judul').val(judul);
            $('#view_deskripsi').val(deskripsi);
            $('#username').val('{{ auth()->user()->username }}');

            // Handle Kode FTA
            // if (kodeFta) {
            //     $('#field_kode_fta_view').show();
            //     $('#view_kode_fta').val(kodeFta);
            // } else {
            //     $('#field_kode_fta_view').hide();
            //     $('#view_kode_fta').val('');
            // }

            if (filePath && filePath.trim() !== '') {
                var fullUrl = `/storage/${filePath}`;
                var fileExtension = filePath.split('.').pop().toLowerCase();

                // Buka di tab baru
                $('#view_file_link').attr('href', fullUrl);

                // Link download
                $('#view_file_download').attr('href', `${prefix}/repository/mahasiswa/${kategori}/${$(this).data('id')}/download`);

                // Preview file
                if (['pdf', 'png', 'jpg', 'jpeg'].includes(fileExtension)) {
                    $('#viewDocumentPreview').attr('src', fullUrl).show();
                    $('#viewPreviewNotAvailable').hide();
                } else if (['doc', 'docx', 'ppt', 'pptx'].includes(fileExtension)) {
                    var viewerUrl = `https://docs.google.com/gview?url=${location.origin}${fullUrl}&embedded=true`;
                    $('#viewDocumentPreview').attr('src', viewerUrl).show();
                    $('#viewPreviewNotAvailable').hide();
                } else {
                    $('#viewDocumentPreview').hide();
                    $('#viewPreviewNotAvailable').show();
                }
            } else {
                $('#viewDocumentPreview').hide();
                $('#viewPreviewNotAvailable').show();
            }
        };
    </script>
@stop
