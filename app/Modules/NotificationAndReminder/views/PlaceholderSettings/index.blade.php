@extends('adminlte::page')

@section('title', 'Pengaturan Placeholder Notifikasi')

@section('content_header')
    <h1>Pengaturan Placeholder Notifikasi</h1>
@stop

@section('content')
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabel Daftar Placeholder --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Notifikasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($placeholders as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item['judul'] }}</td>
                <td>
                    <button 
                        class="btn btn-warning btn-sm btn-edit"
                        data-judul="{{ $item['judul'] }}"
                        data-placeholders='@json($item["placeholders"])'
                    >
                        Edit
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Edit Placeholder -->
    <div class="modal fade" id="editPlaceholderModal" tabindex="-1" role="dialog" aria-labelledby="editPlaceholderLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="editPlaceholderForm" method="POST" action="{{ route('placeholder.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPlaceholderLabel">Edit Placeholder</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="judul_notifikasi" id="modalJudulNotifikasi" />

                        <div class="form-group">
                            <label for="modalPlaceholders">Placeholders (format JSON)</label>
                            <textarea class="form-control" id="modalPlaceholders" name="placeholders" rows="10" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    $(document).ready(function () {
        $('.btn-edit').on('click', function () {
            const judul = $(this).data('judul');
            const placeholders = $(this).data('placeholders');

            $('#modalJudulNotifikasi').val(judul);
            $('#modalPlaceholders').val(JSON.stringify(placeholders, null, 4));

            $('#editPlaceholderModal').modal('show');
        });
    });
</script>
@stop
    