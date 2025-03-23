@extends('adminlte::page')

@section('title', 'PENILAIAN SEMINAR II')

@section('content_header')
    <div class="container-fluid p-3">
        @component('KelolaPenilaianTA.views.components.breadcrumb', [
            'links' => [
                ['url' => url('/kelola-penilaian-ta'), 'label' => 'Home'],
                ['url' => url('/kelola-penilaian-ta/nilai-seminar/3'), 'label' => 'Penilaian Seminar II'],
                ['url' => '', 'label' => 'Masukan Seminar II']
            ]
        ])
        @endcomponent

        <!-- Judul Halaman -->
        <h1 class="mb-0">MASUKAN SEMINAR II</h1>
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
                <strong>Pada hari/tanggal</strong> <br>
                <span>{{ $data['tanggal'] }}</span>
            </div>
            <div class="col-md-2 mt-3">
                <strong>Waktu</strong> <br>
                <span>{{ $data['start'] }}</span>
            </div>
            <div class="col-md-2 mt-3">
                <strong>ID KoTA</strong> <br>
                <span>{{ $data['kota'] }}</span>
            </div>
        </div>

        <h3 class="heading-spacing text-center">ISI MASUKAN</h3>

        <!-- Form -->
        <form action="{{ url('kelola-penilaian-ta/nilai-seminar/' . $id . '/masukan/' . $data['kota'] . '/tambah') }}" method="POST">
        <!-- <form action="{{ url('/kelola-penilaian-ta/pengelolaan-nilai/t') }}" method="POST"> -->
            @csrf

            @foreach ($aspekFeedback as $index=> $feedback)
                <div class="form-group">
                    <label for="{{ Str::slug($feedback->nama_aspek_feedback) }}">
                        Masukan untuk {{ $feedback->nama_aspek_feedback }}
                    </label>
                    <input type="hidden" name="feedback[{{ $index }}][id_fta]" value="{{ $id }}">
                    <input type="hidden" name="feedback[{{ $index }}][nama_aspek_feedback]" value="{{ $feedback->nama_aspek_feedback }}">

                    <input id="feedback-{{ $index }}" type="hidden" name="feedback[{{ $index }}][masukan]" value="">
                    
                    <!-- Trix Editor -->
                    <trix-editor input="feedback-{{ $index }}"></trix-editor>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Kirim Masukan</button>
        </form>
    </div>
@stop

@section('css')
    <!-- Trix Editor Styling -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">
    <link rel="stylesheet" href="{{ asset('KelolaPenilaianTA/css/pemberian_nilai_dan_feedback.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@stop

@section('js')
    <!-- Trix Editor Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>
    <script src="{{ asset('KelolaPenilaianTA/js/pemberian_nilai_dan_feedback.js') }}"></script>
    <script> 
        document.addEventListener("trix-change", function(event) {
            let editor = event.target;
            let hiddenInput = document.getElementById(editor.getAttribute("input"));
            hiddenInput.value = editor.value;
        });
        document.addEventListener("trix-change", function(event) {
            let editor = event.target;
            let inputId = editor.getAttribute("input");
            document.getElementById(inputId).value = event.target.editor.getDocument().toString();
        });
    </script>
@stop
