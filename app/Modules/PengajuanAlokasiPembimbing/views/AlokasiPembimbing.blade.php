@extends('adminlte::page')

@section('title', 'Alokasi Dosen Pembimbing')

@section('content_header')
    <h1>Alokasi Dosen Pembimbing</h1>
@stop

@section('content')
    <div class="p-4">
        <table id="alokasiTable" class="table table-bordered text-center">
            <thead>
                <tr class="bg-dark text-white">
                    <th rowspan="2" class="align-middle">No</th>
                    <th rowspan="2" class="align-middle">Kelompok</th>
                    <th rowspan="2" class="align-middle">Jumlah Mahasiswa</th>
                    <th rowspan="2" class="align-middle">Topik</th>
                    <th rowspan="2" class="align-middle">Judul</th>
                    <th colspan="5">Alokasi</th>
                    <th colspan="2" class="align-middle">Pembimbing</th>
                </tr>
                <tr class="bg-secondary text-white">
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                    <th>1</th>
                    <th>2</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $row)
                    <tr class="bg-light">
                        <td class="align-middle">{{ $index + 1 }}</td>
                        <td class="align-middle position-relative">
                            <span class="text-primary cursor-pointer"
                                onmouseover="showTooltip({{ $index }})"
                                onmouseout="hideTooltip({{ $index }})">
                                {{ $row['kota'] }}
                            </span>
                            <div id="tooltip-kota-{{ $index }}"
                                class="tooltip-box d-none position-absolute bg-white border p-3 shadow rounded text-left">
                                <strong>Anggota Kelompok:</strong>
                                <ul class="pl-3 m-0">
                                    @foreach ($row['anggota'] as $anggota)
                                        <li>{{ $anggota }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </td>
                        <td class="align-middle">{{ $row['jumlahMahasiswa'] }}</td>
                        <td class="align-middle">{{ $row['topik'] }}</td>
                        <td class="align-middle">{{ $row['judul'] }}</td>
                        @foreach ($row['usulanDosen'] as $i => $dosen)
                            <td class="align-middle position-relative">
                                <span class="text-success cursor-pointer"
                                    onmouseover="showTooltipDosen({{ $index }}, {{ $i }})"
                                    onmouseout="hideTooltipDosen({{ $index }}, {{ $i }})">
                                    {{ $dosen }}
                                </span>
                                <div id="tooltip-dosen-{{ $index }}-{{ $i }}"
                                    class="tooltip-box d-none position-absolute bg-white border p-3 shadow rounded text-left">
                                    <strong>Nama Dosen: {{ $dosen }}</strong>
                                    <p class="m-0">Sisa Kuota D4: <strong>2</strong></p>
                                    <p class="m-0">Sisa Kuota D3: <strong>1</strong></p>
                                    <p class="m-0">Quota Mahasiswa D4: <strong>5</strong></p>
                                    <p class="m-0">Quota Mahasiswa D3: <strong>4</strong></p>
                                </div>
                            </td>
                        @endforeach
                        <td class="align-middle">
                            <input type="text" class="form-control text-center" value="{{ $row['pembimbing1'] }}">
                        </td>
                        <td class="align-middle">
                            <input type="text" class="form-control text-center" value="{{ $row['pembimbing2'] }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Tombol Aksi --}}
    <div class="d-flex justify-content-end mt-4">
        <button type="button" class="btn btn-dark mr-2">Cek Rekap</button>
        <button type="button" class="btn btn-secondary mr-2">Simpan</button>
        <button type="button" class="btn btn-primary">Submit</button>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        .tooltip-box {
            width: 300px;
            padding: 10px;
            border-radius: 6px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 14px;
            line-height: 1.5;
            left: 0;
            transform: translateX(0);
            top: 120%;
            z-index: 10;
        }
        .tooltip-box ul {
            padding-left: 15px;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#alokasiTable').DataTable({
                "paging": true,
                "lengthMenu": [10, 25, 50],
                "pageLength": 10,
                "searching": true,
                "ordering": true,
                "info": true,
                "columnDefs": [
                    { "orderable": false, "targets": [5, 6, 7, 8, 9, 10, 11] } // Nonaktifkan sorting untuk kolom Alokasi & Pembimbing
                ],
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada data ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(disaring dari total _MAX_ data)",
                    "search": "Cari:",
                    "paginate": {
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        });

        function showTooltip(index) {
            document.getElementById('tooltip-kota-' + index).classList.remove('d-none');
        }

        function hideTooltip(index) {
            document.getElementById('tooltip-kota-' + index).classList.add('d-none');
        }

        function showTooltipDosen(index, i) {
            document.getElementById('tooltip-dosen-' + index + '-' + i).classList.remove('d-none');
        }

        function hideTooltipDosen(index, i) {
            document.getElementById('tooltip-dosen-' + index + '-' + i).classList.add('d-none');
        }
    </script>
@stop
