<div class = "table-responsive">
    <table class = "table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Mahasiswa</th>
                <th>NIM</th>
                <th>Prodi</th>
                <th>Angkatan</th>
                <th>Usulan Pembimbing</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuan as $key => $value)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $value->nama_mahasiswa }}</td>
                    <td>{{ $value->nim }}</td>
                    <td>{{ $value->prodi }}</td>
                    <td>{{ $value->angkatan }}</td>
                    <td>{{ $value->usulan_pembimbing }}</td>
                    <td>{{ $value->status }}</td>
                    <td>
                        <a href="{{ route('pengajuan.detail', $value->id) }}" class="btn btn-primary btn-sm">Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>