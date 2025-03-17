<div class="container-fluid w-100 justify-content-center align-items-center d-flex" style="height: 70vh;">
    <div class="row">
        <div class="col-auto">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Konfirmasi Bersedia Membimbing</h3>
                </div>
                <div class="card-body">
                    Apakah anda atas nama .... bersedia untuk menjadi pembimbing tugas akhir?
                    <br>
                    <br>
                    <center>
                        {{-- <button type="button" class="btn btn-success">Bersedia</button>
                        <button type="button" class="btn btn-danger ml-3">Tidak Bersedia</button> --}}
                        <div>
                            <form action="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.konfirmasi-kesediaan', 'bersedia') }}" method="post" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">Bersedia</button>
                            </form>
                            <form action="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.konfirmasi-kesediaan', 'tidak_bersedia') }}" method="post" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger ml-3">Tidak Bersedia</button>
                            </form>
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </div>

</div>
