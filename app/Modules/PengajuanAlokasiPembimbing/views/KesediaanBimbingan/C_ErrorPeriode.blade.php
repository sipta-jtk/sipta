@if ($savedInformation['StatusBersediaMembimbing'] == 'tidak_bersedia')
    <div class="alert alert-warning" role="alert">
        Anda tidak bersedia untuk menjadi pembimbing tugas akhir, formulir ini <strong>tidak perlu</strong> diisi.
        <form
            action="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.konfirmasi-kesediaan', 'belum_konfirmasi') }}"
            method="post" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Ubah Kesediaan
                membimbing</button>
        </form>
    </div>
@elseif ($savedInformation['StatusBersediaMembimbing'] == 'bersedia' && $savedInformation['Periode'])
    <div class="alert alert-success" role="alert">
        Anda telah bersedia untuk menjadi pembimbing tugas akhir, formulir ini <strong>wajib</strong> diisi.
        <form
            action="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.konfirmasi-kesediaan', 'belum_konfirmasi') }}"
            method="post" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Ubah Kesediaan
                membimbing</button>
        </form>
    </div>
@endif
@if (
    !$savedInformation['Periode'] &&
        route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.index') != url()->current())
    <div class="alert alert-danger" role="alert">
        Periode Pengisian FTA1 (Formulir kesediaan membimbing) belum dibuka.
    </div>
@endif
