<table class="table table-bordered">
   <div class="d-flex justify-content-between mb-3">
    @if($bidang->bidang != null)
        <span > Bidang : {{$bidang->bidang}}</span>
    @else
        <span> Bidang : - </span>
    @endif
    </div>

    <thead>
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Kelas</th>
        </tr>
    </thead>
    <tbody>
        </td>
        @forelse($kota as $data)
        <tr>
            <td>{{ $data->nim }}</td>
            <td>{{ $data->nama}}</td>
            <td>{{ $data->kelas}}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center">Tidak ada data mahasiswa</td>
        </tr>
        @endforelse
    </tbody>
</table>
