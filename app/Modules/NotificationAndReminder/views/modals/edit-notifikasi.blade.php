<!-- Button Edit -->
<a title="Edit" class="btn btn-warning btn-sm my-1 mx-1" data-toggle="modal" data-target="#editNotifikasiModal-{{ $notifikasi->id_template_notifikasi }}" style="cursor: pointer;">
    <i class="fas fa-edit"></i>
</a>

<!-- Modal -->
<x-adminlte-modal id="editNotifikasiModal-{{ $notifikasi->id_template_notifikasi }}" title="Ubah Notifikasi" theme="blue" size="lg">
    <form onsubmit="return false;">
        @csrf
        <div class="px-3 pt-3">
            <div class="mb-3">
                <label for="judul">Judul Notifikasi</label>
                <x-adminlte-input name="judul" placeholder="[Pemberitahuan] Dosen Pembimbing" required />
            </div>

            <div class="mb-3">
                <label for="jenis">Jenis Notifikasi</label>
                <x-adminlte-select name="jenis" required>
                    <option value="pemberitahuan">Pemberitahuan</option>
                    <option value="peringatan">Peringatan</option>
                </x-adminlte-select>
            </div>

            <div class="mb-3">
                <p class="text-secondary text-md border-bottom">Pola Pada Aplikasi</p>
                <x-adminlte-textarea name="pola_aplikasi" rows=4 placeholder="NIP Dosen: {nip}&#10;Nama Dosen: {nama}" required />
            </div>

            <div class="mb-3">
                <p class="text-secondary text-md border-bottom">Pola Pada Email</p>
                <x-adminlte-textarea name="pola_email" rows=4 placeholder="Halo {nama},&#10;Kami ingin memberitahukan bahwa..." required />
            </div>
        </div>

        <div class="d-flex justify-content-end px-3 pb-3">
            <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal" class="mx-1" />
            <x-adminlte-button theme="success" label="Simpan" type="submit" class="mx-1" />
        </div>

        <x-slot name="footerSlot"></x-slot>
    </form>
</x-adminlte-modal>