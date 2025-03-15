@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
@stop

@section('content')
<div class="container">
    @if(isset($kelompokData) && count($kelompokData) > 0)
    <table id="kesediaanTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Kelompok TA</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Bidang</th>
                <th>Judul TA</th>
                <th>Tanggal Pengajuan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($kelompokData as $kelompok)
            @foreach ($kelompok['anggota'] as $index => $anggota)
            <tr class="kelompok-row" data-id="{{ $kelompok['id'] }}">
                <td class="merge-col" data-value="{{ $kelompok['id'] }}">{{ $no }}</td>
                @if($index === 0)
                <td class="merge-col" data-value="{{ $kelompok['kode'] }}">{{ $kelompok['kode'] }}</td>
                <td>{{ $anggota['nama'] }}</td>
                <td>{{ $anggota['nim'] }}</td>
                <td class="merge-col" data-value="{{ $kelompok['bidang'] }}">{{ $kelompok['bidang'] }}</td>
                <td class="merge-col" data-value="{{ $kelompok['judul'] }}">{{ $kelompok['judul'] }}</td>
                <td class="merge-col" data-value="{{ $kelompok['tanggal'] }}">{{ $kelompok['tanggal'] }}</td>
                @else
                <td>&nbsp;</td>
                <td>{{ $anggota['nama'] }}</td>
                <td>{{ $anggota['nim'] }}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                @endif
            </tr>
            @endforeach
            @php $no++; @endphp
            @endforeach
        </tbody>
    </table>
    @else
    <p>Tidak ada data tersedia.</p>
    @endif
</div>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#kesediaanTable').DataTable({
            "paging": true
            , "searching": true
            , "ordering": true
            , "info": true
            , "pageLength": 9
            , "lengthMenu": [
                [6, 9, 10, 25, 50]
                , [6, 9, 10, 25, 50]
            ]
            , "order": [
                [0, "asc"]
            ]
            , "language": {
                "search": "Cari:"
                , "lengthMenu": "Tampilkan _MENU_ data per halaman"
                , "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                , "paginate": {
                    "first": "Awal"
                    , "last": "Akhir"
                    , "next": "Selanjutnya"
                    , "previous": "Sebelumnya"
                }
            }
            , "drawCallback": function(settings) {
                var seenNumbers = {};

                $('#kesediaanTable tbody tr').each(function() {
                    var kelompokId = $(this).attr('data-id');

                    // Sembunyikan nomor jika sudah muncul sebelumnya
                    var nomorCell = $(this).find('td:first');
                    if (seenNumbers[kelompokId]) {
                        nomorCell.text('').css('visibility', 'hidden');
                    } else {
                        seenNumbers[kelompokId] = true;
                    }
                });
            }
        });
    });

</script>


@stop
