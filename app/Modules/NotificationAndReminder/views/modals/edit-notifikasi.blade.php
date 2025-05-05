<!-- Button Edit -->
<a title="Edit" class="btn btn-warning btn-sm my-1 mx-1" data-toggle="modal" data-target="#editNotifikasiModal-{{ $notifikasi->id_template_notifikasi }}" style="cursor: pointer;">
    <i class="fas fa-edit"></i>
</a>

<!-- Modal -->
<x-adminlte-modal id="editNotifikasiModal-{{ $notifikasi->id_template_notifikasi }}" title="Ubah Notifikasi" theme="blue" size="lg">
    <form action="{{ route('notifikasi.update', $notifikasi->id_template_notifikasi) }}" method="POST">
        @csrf
        @method("POST")
        <div class="px-3 pt-3">
            <div class="mb-3">
                <label for="judul">Judul Notifikasi</label>
                <x-adminlte-input name="judul_notifikasi" value="{{ $notifikasi->judul_notifikasi }}" required />
            </div>

            <div class="mb-3">
                <label for="jenis">Jenis Notifikasi</label>
                <x-adminlte-select name="jenis_notifikasi" required>
                    <option value="pemberitahuan" {{ $notifikasi->jenis_notifikasi == 'pemberitahuan' ? 'selected' : '' }}>Pemberitahuan</option>
                    <option value="reminder" {{ $notifikasi->jenis_notifikasi == 'reminder' ? 'selected' : '' }}>Pengingat</option>
                </x-adminlte-select>
            </div>

            <div class="mb-3">
                <p class="text-secondary text-md border-bottom">Pola Pada Aplikasi</p>
                <x-adminlte-textarea name="isi_in_apps" rows=4 required>
                    {{ $notifikasi->isi_in_apps }}
                </x-adminlte-textarea>
            </div>

            <div class="mb-3">
                <p class="text-secondary text-md border-bottom">Pola Pada Email</p>
                <x-adminlte-textarea name="isi_in_email" rows=4 required>
                    {{ $notifikasi->isi_in_email }}
                </x-adminlte-textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end px-3 pb-3">
            <x-adminlte-button theme="danger" label="Tutup" data-dismiss="modal" class="mx-1" />
            <x-adminlte-button theme="success" label="Simpan" type="submit" class="mx-1" />
        </div>

        <x-slot name="footerSlot"></x-slot>
    </form>
</x-adminlte-modal>