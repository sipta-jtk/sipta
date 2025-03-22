@extends('adminlte::page')

@section('title', 'PengajuanAlokasiPembimbing')

@section('content_header')
    <h1>Jadwal Dosen Membimbing</h1>
@stop

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet" />
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@stop

@section('content')
    <p>Beranda > <a href="#">Jadwal Dosen Membimbing</a></p>

    <div class="table-responsive">
        @if (count($groupedData) > 0)
            <table id="" class="table table-bordered bg-white">
                <thead>
                    <tr class="bg-dark text-white">
                        <th class="text-center" style="min-width: 0.5vw;">No</th>
                        <th class="text-center">Nama Dosen</th>
                        <th class="text-center">Hari</th>
                        <th class="text-center">Jam Awal</th>
                        <th class="text-center">Jam Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter = 1; @endphp 

                    @foreach ($groupedData as $dosen)
                        @php 
                            // Count total rowspan for the lecturer
                            $rowspan_dosen = count($dosen['jadwal']);
                            $groupedByDay = [];

                            // Group schedules by day
                            foreach ($dosen['jadwal'] as $jadwal) {
                                $groupedByDay[$jadwal['hari']][] = $jadwal;
                            }
                        @endphp

                        @foreach ($groupedByDay as $hari => $jadwal_list)
                            @php $rowspan_hari = count($jadwal_list); @endphp

                            @foreach ($jadwal_list as $index => $jadwal)
                                <tr>
                                    @if ($index == 0 && $loop->parent->first)
                                        <td class="text-center align-middle" rowspan="{{ $rowspan_dosen }}">{{ $counter }}</td>
                                        <td class="text-center align-middle" rowspan="{{ $rowspan_dosen }}">{{ $dosen['nama_dosen'] }}</td>
                                        @php $counter++; @endphp 
                                    @endif

                                    @if ($index == 0)
                                        <td class="text-center align-middle" rowspan="{{ $rowspan_hari }}">{{ ucfirst($hari) }}</td>
                                    @endif
                                    
                                    <td class="text-center align-middle">{{ date('H:i', strtotime($jadwal['jam_mulai'])) }}</td>
                                    <td class="text-center align-middle">{{ date('H:i', strtotime($jadwal['jam_selesai'])) }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center">Tidak ada data jadwal dosen membimbing yang tersedia.</p>
        @endif
    </div>
@stop
