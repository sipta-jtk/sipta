@extends('adminlte::page')

@section('title', 'MASUKAN SEMINAR II')

@section('content_header')
    <div class="container-fluid p-3">
        <!-- Breadcrumb -->
        {{-- TBD perbaiki alur breadcumb --}}
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => route('beranda.get'), 'label' => 'Home'],
                ['url' => route('pengisian.nilai', ['namaFta' => $data['namaFta'], 'idKota' => $data['id_kota'], 'idProdi' => $seminar['id_prodi']]), 'label' => 'Penilaian Seminar II'],
                ['url' => '', 'label' => 'Masukan Seminar II']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">MASUKAN {{ strtoupper($data['namaFta']) }}</h1>
    </div>
@stop

@section('content')
    <div class="card p-4">
        <div class="row">
            <!-- Kode FTA -->
            <div class="col-md-12">
                <strong>Kode FTA</strong> <br>
                <span>{{ $data['kode_fta'] }}</span>
            </div>

            <!-- Tanggal, Waktu, ID Kota -->
            <div class="col-md-2 mt-3">
                <strong>KoTA</strong> <br>
                <span>{{ $data['namaKota'] }}</span>
            </div>
        </div>
        
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
                        <div class="text-muted" id="char-count-{{ $index }}">0/100 karakter</div>
                    </div>

                    <input type="hidden" name="feedback[{{ $index }}][id_fta]" value="{{ $data['kode_fta'] }}">
                    <input type="hidden" name="feedback[{{ $index }}][nama_aspek_feedback]" value="{{ $feedback->nama_aspek_feedback }}">

                    @php
                        $existingFeedback = $data['detailFeedback']->get($feedback->id_feedback);
                        $oldValue = old("feedback.{$index}.masukan", $existingFeedback->isi_feedback ?? '');
                    @endphp

                    <input id="feedback-{{ $index }}" type="hidden" name="feedback[{{ $index }}][masukan]" value="{{ $oldValue }}">
                    <trix-editor input="feedback-{{ $index }}"></trix-editor>
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
    <!-- Trix Editor Styling -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/pemberian_nilai_dan_feedback.css') }}">
@stop

@section('js')
    <!-- Trix Editor Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/pemberian_nilai_dan_feedback.js') }}"></script>
    <script> 
        document.addEventListener("trix-change", function(event) {
            let editor = event.target;
            let inputId = editor.getAttribute("input");
            document.getElementById(inputId).value = event.target.innerHTML;
        });
    </script>
@stop

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const editors = document.querySelectorAll('trix-editor');

        editors.forEach((editor, index) => {
            const counter = document.querySelector(`#char-count-${index}`);
            const hiddenInput = document.querySelector(`#feedback-${index}`);

            const updateCharCount = () => {
                // Ambil teks bersih dari editor
                const plainText = editor.editor.getDocument().toString().trim();
                const charLength = plainText.length;

                counter.textContent = `${charLength}/100 karakter`;

                if (charLength < 15 || charLength > 100) {
                    counter.classList.add('text-danger');
                } else {
                    counter.classList.remove('text-danger');
                }
            };

            editor.addEventListener('trix-change', updateCharCount);

            // Trigger awal biar muncul nilai default
            updateCharCount();
        });
    });

    // Tampilkan jumlah karakter real-time
    document.querySelectorAll('trix-editor').forEach((editor, index) => {
        editor.addEventListener('trix-change', function (e) {
            const hiddenInput = document.querySelector(`#feedback-${index}`);
            const countDisplay = document.querySelector(`#char-count-${index}`);
            const value = hiddenInput.value.trim();
            countDisplay.textContent = `${value.length}/100 karakter`;
        });
    });
</script>
