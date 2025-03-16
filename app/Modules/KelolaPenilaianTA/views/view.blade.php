@extends('adminlte::page')

@section('title', 'Cek Plagiarisme')

@section('content_header')
    <h1>Cek Plagiarisme</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('cekplagiarisme.process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Upload File (PDF/DOCX)</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Cek Plagiarisme</button>
        </form>

        @if(isset($percentage))
            <h3 class="mt-4">Persentase Plagiarisme: <span style="color:red;">{{ $percentage }}%</span></h3>

            @foreach($results as $sentence => $sources)
                <p><strong>{{ $sentence }}</strong></p>
                <ul>
                    @if(is_array($sources))
                        @foreach($sources as $url)
                            <li>
                                @if(strpos($url, 'http') === 0)
                                    <a href="{{ $url }}" target="_blank">{{ $url }}</a>
                                @else
                                    {{ $url }}
                                @endif
                            </li>
                        @endforeach
                    @else
                        <li>{{ $sources }}</li>
                    @endif
                </ul>
            @endforeach
        @endif
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
