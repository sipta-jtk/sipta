<center>
    <div class="row w-100 justify-content-center">
<<<<<<< HEAD
        <div class="col-lg-3 col ml-2 mr-2">
=======
        <div class="col-md-3 col-12 ml-2 mr-2">
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
            <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.card-banner type="info"
                innerHtml="<h3 id='jmlMinatBidangText'>{{ $savedInformation['BidangInterestTotal'] }}</h3><p>Bidang Diminati</p>"
                icon="fas fa-graduation-cap"
                href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index') }}"
                hrefText="More info" />
        </div>
<<<<<<< HEAD
        <div class="col-lg-3 col ml-2 mr-2">
            <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.card-banner type="success"
                innerHtml="<h3 class='mb-2'>{{ $savedInformation['MaxBimbingan']['MaxD3'] }}<sup style='font-size: 20px'> mahasiswa D3</sup><br>{{ $savedInformation['MaxBimbingan']['MaxD4'] }}<sup style='font-size: 20px'> mahasiswa D4</sup></h3>"
=======
        @php
            $totalMahasiswa = 0;
            foreach ($savedInformation['MaxBimbingan'] as $key => $value) {
                $totalMahasiswa += $value['jumlah'];
            }
        @endphp
        <div class="col-md-3 col-12 ml-2 mr-2">
            <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.card-banner type="success"
                innerHtml="<h3 class='mb-2'>{{ $totalMahasiswa }}<br><sup style='font-size: 20px'> total mahasiswa</sup></h3>"
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
                icon="fas fa-users"
                href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa.index') }}"
                hrefText="More info" />
        </div>
<<<<<<< HEAD
        <div class="col-lg-3 col ml-2 mr-2">
            <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.card-banner type="warning"
                innerHtml="<h3>{{ $savedInformation['JadwalTotal']['Day'] }} <sup style='font-size: 20px'>Hari</sup><br>{{ $savedInformation['JadwalTotal']['Session'] }} <sup style='font-size: 20px'>Sesi</sup></h3>"
                icon="fas fa-calendar-alt" href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.index') }}"
                hrefText="More info" />
        </div>
    </div>
</center>
=======
        <div class="col-md-3 col-12 ml-2 mr-2">
            <x-pengajuan-alokasi-pembimbing.components.kesediaan-membimbing.card-banner type="warning"
                innerHtml="<h3>{{ $savedInformation['JadwalTotal']['Day'] }} <sup style='font-size: 20px'>Hari</sup><br>{{ $savedInformation['JadwalTotal']['Session'] }} <sup style='font-size: 20px'>Sesi</sup></h3>"
                icon="fas fa-calendar-alt"
                href="{{ route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.index') }}"
                hrefText="More info" />
        </div>
    </div>
</center>
>>>>>>> 58f96f1821308e854679e72c61ec57c4ccb8db0b
